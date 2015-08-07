<?php

namespace Metroplex\Edifact;

/**
 * Convert EDI messages into tokens for parsing.
 */
class Tokenizer
{
    use ControlCharacterTrait;

    /**
     * @var string $message The message that we are tokenizing.
     */
    protected $message;

    /**
     * @var string|null $char The current character from the message we are dealing with.
     */
    protected $char;

    /**
     * @var string $string The stored characters for the next token.
     */
    protected $string;

    /**
     * @var bool $isEscaped If the current character has been esacped.
     */
    protected $isEscaped = false;


    /**
     * Convert the passed message into tokens.
     *
     * @param string $message The EDI message
     *
     * @return Token[]
     */
    public function getTokens($message)
    {
        $this->message = $message;
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
    protected function readNextChar()
    {
        $this->char = $this->getNextChar();

        # If the last character was escaped, this one can't possibly be
        if ($this->isEscaped) {
            $this->isEscaped = false;
        }

        # If this is the escape character, then read the next one and flag the next as escaped
        if ($this->char === $this->escapeCharacter) {
            $this->char = $this->getNextChar();
            $this->isEscaped = true;
        }
    }


    /**
     * Get the next character from the message.
     *
     * @return void
     */
    protected function getNextChar()
    {
        $char = mb_substr($this->message, 0, 1);
        $this->message = mb_substr($this->message, 1);

        return $char;
    }


    /**
     * Get the next token from the message.
     *
     * @return Token|null
     */
    protected function getNextToken()
    {
        if ($this->endOfMessage()) {
            return;
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
                while (in_array($this->char, ["\r", "\n"])) {
                    $this->readNextChar();
                }

                return $token;
            }
        }

        while (!$this->isControlCharacter()) {
            if ($this->endOfMessage()) {
                throw new \RuntimeException("Unexpected end of EDI message");
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
    protected function isControlCharacter()
    {
        if ($this->isEscaped) {
            return false;
        }
        return in_array($this->char, [$this->componentSeparator, $this->dataSeparator, $this->segmentTerminator]);
    }


    /**
     * Store the current character and read the next one from the message.
     *
     * @return void
     */
    protected function storeCurrentCharAndReadNext()
    {
        $this->string .= $this->char;
        $this->readNextChar();
    }


    /**
     * Get the previously stored characters.
     *
     * @return string
     */
    protected function extractStoredChars()
    {
        $string = $this->string;

        $this->string = "";

        return $string;
    }


    /**
     * Check if we've reached the end of the message
     *
     * @return void
     */
    protected function endOfMessage()
    {
        return strlen($this->char) == 0;
    }
}
