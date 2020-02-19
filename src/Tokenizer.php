<?php

namespace Metroplex\Edifact;

use Metroplex\Edifact\Control\CharactersInterface as ControlCharactersInterface;
use Metroplex\Edifact\Exceptions\ParseException;
use function in_array;
use function strlen;
use function substr;

/**
 * Convert EDI messages into tokens for parsing.
 */
final class Tokenizer
{
    /**
     * @var string $message The message that we are tokenizing.
     */
    private $message;

    /**
     * @var int The current position in the message.
     */
    private $position = 0;

    /**
     * @var string $char The current character from the message we are dealing with.
     */
    private $char = "";

    /**
     * @var string $string The stored characters for the next token.
     */
    private $string;

    /**
     * @var bool $isEscaped If the current character has been esacped.
     */
    private $isEscaped = false;

    private $isControlCharacter = false;

    private $controlCharacters = [];
    private $escapeCharacter = null;
    private $componentSeparator = null;
    private $dataSeparator = null;
    private $segmentTerminator = null;

    /**
     * Convert the passed message into tokens.
     *
     * @param string $message The EDI message
     * @param ControlCharactersInterface $characters
     *
     * @return Token[]
     * @throws ParseException
     */
    public function getTokens(string $message, ControlCharactersInterface $characters): array
    {
        $this->message = $message;
        $this->escapeCharacter = $characters->getEscapeCharacter();
        $this->componentSeparator = $characters->getComponentSeparator();
        $this->dataSeparator = $characters->getDataSeparator();
        $this->segmentTerminator = $characters->getSegmentTerminator();
        $this->controlCharacters = [
            $this->componentSeparator,
            $this->dataSeparator,
            $this->segmentTerminator
        ];
        $this->char = "";
        $this->string = "";

        $this->readNextChar();

        $tokens = [];
        while ($token = $this->getNextToken()) {
            $tokens[] = $token;
        }

        return $tokens;
    }


    /**
     * Read the next character from the message.
     *
     * @return void
     */
    private function readNextChar(): void
    {
        $this->char = substr($this->message, $this->position, 1);
        ++$this->position;

        # If this is the escape character, then read the next one and flag the next as escaped
        if ($this->char === $this->escapeCharacter) {
            $this->char = substr($this->message, $this->position, 1);
            ++$this->position;
            $this->isEscaped = true;
            $this->isControlCharacter = false;
        } else {
            $this->isEscaped = false;
            $this->isControlCharacter = in_array($this->char, $this->controlCharacters);
        }
    }


    /**
     * Get the next character from the message.
     *
     * @return string
     */
    private function getNextChar(): string
    {
        $char = substr($this->message, $this->position, 1);
        ++$this->position;

        return $char;
    }


    /**
     * Get the next token from the message.
     *
     * @return Token|null
     * @throws ParseException
     */
    private function getNextToken(): ?Token
    {
        if ($this->endOfMessage()) {
            return null;
        }

        # If we're not escaping this character then see if it's a control character
        if (!$this->isEscaped) {
            if ($this->char === $this->componentSeparator) {
                $this->storeCurrentCharAndReadNext();
                return new Token(Token::COMPONENT_SEPARATOR, $this->extractStoredChars());
            }

            if ($this->char === $this->dataSeparator) {
                $this->storeCurrentCharAndReadNext();
                return new Token(Token::DATA_SEPARATOR, $this->extractStoredChars());
            }

            if ($this->char === $this->segmentTerminator) {
                $this->storeCurrentCharAndReadNext();
                $token = new Token(Token::TERMINATOR, $this->extractStoredChars());

                # Ignore any trailing space after the end of the segment
                while (in_array($this->char, ["\r", "\n"], true)) {
                    // readNextChar() inline begin
                    $this->char = substr($this->message, $this->position, 1);
                    ++$this->position;

                    # If this is the escape character, then read the next one and flag the next as escaped
                    if ($this->char === $this->escapeCharacter) {
                        $this->char = substr($this->message, $this->position, 1);
                        ++$this->position;
                        $this->isEscaped = true;
                        $this->isControlCharacter = false;
                    } else {
                        $this->isEscaped = false;
                        $this->isControlCharacter = in_array($this->char, $this->controlCharacters);
                    }
                    // readNextChar() inline end
                }

                return $token;
            }
        }

        while (!$this->isControlCharacter) {
            if ($this->endOfMessage()) {
                throw new ParseException("Unexpected end of EDI message");
            }
            $this->storeCurrentCharAndReadNext();
        }

        return new Token(Token::CONTENT, $this->extractStoredChars());
    }


    /**
     * Check if the current character is a control character.
     *
     * @return bool
     */
    private function isControlCharacter(): bool
    {
        if ($this->isEscaped) {
            return false;
        }

        if ($this->char === $this->componentSeparator) {
            return true;
        }

        if ($this->char === $this->dataSeparator) {
            return true;
        }

        if ($this->char === $this->segmentTerminator) {
            return true;
        }

        return false;
    }


    /**
     * Store the current character and read the next one from the message.
     *
     * @return void
     */
    private function storeCurrentCharAndReadNext(): void
    {
        $this->string .= $this->char;
        // readNextChar() inline begin
        $this->char = substr($this->message, $this->position, 1);
        ++$this->position;

        # If this is the escape character, then read the next one and flag the next as escaped
        if ($this->char === $this->escapeCharacter) {
            $this->char = substr($this->message, $this->position, 1);
            ++$this->position;
            $this->isEscaped = true;
            $this->isControlCharacter = false;
        } else {
            $this->isEscaped = false;
            $this->isControlCharacter = in_array($this->char, $this->controlCharacters);
        }
        // readNextChar() inline end
    }


    /**
     * Get the previously stored characters.
     *
     * @return string
     */
    private function extractStoredChars(): string
    {
        $string = $this->string;

        $this->string = "";

        return $string;
    }


    /**
     * Check if we've reached the end of the message
     *
     * @return bool
     */
    private function endOfMessage(): bool
    {
        return strlen($this->char) == 0;
    }
}
