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
     * @var ControlCharactersInterface $character The control characters for the message.
     */
    private $characters;

    /**
     * @var string|null $char The current character from the message we are dealing with.
     */
    private $char;

    /**
     * @var string $string The stored characters for the next token.
     */
    private $string;

    /**
     * @var bool $isEscaped If the current character has been esacped.
     */
    private $isEscaped = false;


    /**
     * Convert the passed message into tokens.
     *
     * @param string $message The EDI message
     *
     * @return Token[]
     */
    public function getTokens($message, ControlCharactersInterface $characters)
    {
        $this->message = $message;
        $this->characters = $characters;
        $this->char = null;
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
    private function readNextChar()
    {
        $this->char = $this->getNextChar();

        # If the last character was escaped, this one can't possibly be
        if ($this->isEscaped) {
            $this->isEscaped = false;
        }

        # If this is the escape character, then read the next one and flag the next as escaped
        if ($this->char === $this->characters->getEscapeCharacter()) {
            $this->char = $this->getNextChar();
            $this->isEscaped = true;
        }
    }


    /**
     * Get the next character from the message.
     *
     * @return string
     */
    private function getNextChar()
    {
        $char = substr($this->message, $this->position, 1);
        ++$this->position;

        return $char;
    }


    /**
     * Get the next token from the message.
     *
     * @return Token|null
     */
    private function getNextToken()
    {
        if ($this->endOfMessage()) {
            return null;
        }

        # If we're not escaping this character then see if it's a control character
        if (!$this->isEscaped) {
            if ($this->char === $this->characters->getComponentSeparator()) {
                $this->storeCurrentCharAndReadNext();
                return new Token(Token::COMPONENT_SEPARATOR, $this->extractStoredChars());
            }

            if ($this->char === $this->characters->getDataSeparator()) {
                $this->storeCurrentCharAndReadNext();
                return new Token(Token::DATA_SEPARATOR, $this->extractStoredChars());
            }

            if ($this->char === $this->characters->getSegmentTerminator()) {
                $this->storeCurrentCharAndReadNext();
                $token = new Token(Token::TERMINATOR, $this->extractStoredChars());

                # Ignore any trailing space after the end of the segment
                while (in_array($this->char, ["\r", "\n"])) {
                    $this->readNextChar();
                }

                return $token;
            }
        }

        while (!$this->isControlCharacter()) {
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
    private function isControlCharacter()
    {
        if ($this->isEscaped) {
            return false;
        }

        if ($this->char === $this->characters->getComponentSeparator()) {
            return true;
        }

        if ($this->char === $this->characters->getDataSeparator()) {
            return true;
        }

        if ($this->char === $this->characters->getSegmentTerminator()) {
            return true;
        }

        return false;
    }


    /**
     * Store the current character and read the next one from the message.
     *
     * @return void
     */
    private function storeCurrentCharAndReadNext()
    {
        $this->string .= $this->char;
        $this->readNextChar();
    }


    /**
     * Get the previously stored characters.
     *
     * @return string
     */
    private function extractStoredChars()
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
    private function endOfMessage()
    {
        return strlen($this->char) == 0;
    }
}
