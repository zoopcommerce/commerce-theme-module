<?php

namespace Zoop\Theme\Test\Lexer;

use Zoop\Theme\Test\AbstractTest;
use Zoop\Theme\Lexer\Lexer;
use Zoop\Theme\Lexer\Regex;
use Zoop\Theme\Tokenizer\Token\TextToken;
use Zoop\Theme\Tokenizer\Token\ImageTokenInterface;
use Zoop\Theme\Tokenizer\Token\CssTokenInterface;
use Zoop\Theme\Tokenizer\Token\JavascriptTokenInterface;

class HtmlLexerTest extends AbstractTest
{
    public function testTokenizeHtmlCssUrl()
    {
        $relativePath = __DIR__ . '/../Assets';
        $content = file_get_contents($relativePath . '/simple.html');

        $lexer = new Lexer;
        $lexer->setRelativeFilePath($relativePath);
        $lexer->addRegex(new Regex\HtmlCssUrlRegex);

        $tokenStream = $lexer->tokenize($content);

        $this->assertCount(19, $tokenStream->getTokens());

        $textTokens = [];
        $cssTokens = [];
        foreach ($tokenStream->getTokens() as $token) {
            if ($token instanceof TextToken) {
                $textTokens[] = $token;
            } elseif ($token instanceof CssTokenInterface) {
                $cssTokens[] = $token;
            }
        }

        $this->assertCount(17, $textTokens);
        $this->assertCount(2, $cssTokens);
        $this->assertEquals('bootstrap.css', (string) $cssTokens[0]);
        $this->assertEquals('simple.css', (string) $cssTokens[1]);
    }

    public function testTokenizeHtmlImageUrl()
    {
        $relativePath = __DIR__ . '/../Assets';
        $content = file_get_contents($relativePath . '/simple.html');

        $lexer = new Lexer;
        $lexer->setRelativeFilePath($relativePath);
        $lexer->addRegex(new Regex\HtmlImageUrlRegex);

        $tokenStream = $lexer->tokenize($content);

        $this->assertCount(17, $tokenStream->getTokens());

        $textTokens = [];
        $imageTokens = [];
        foreach ($tokenStream->getTokens() as $token) {
            if ($token instanceof TextToken) {
                $textTokens[] = $token;
            } elseif ($token instanceof ImageTokenInterface) {
                $imageTokens[] = $token;
            }
        }

        $this->assertCount(16, $textTokens);
        $this->assertCount(1, $imageTokens);
        $this->assertEquals('zoop.jpg', (string) $imageTokens[0]);
    }

    public function testTokenizeHtmlJavascriptUrl()
    {
        $relativePath = __DIR__ . '/../Assets';
        $content = file_get_contents($relativePath . '/simple.html');

        $lexer = new Lexer;
        $lexer->setRelativeFilePath($relativePath);
        $lexer->addRegex(new Regex\HtmlJavascriptUrlRegex);

        $tokenStream = $lexer->tokenize($content);

        $this->assertCount(17, $tokenStream->getTokens());

        $textTokens = [];
        $jsTokens = [];
        foreach ($tokenStream->getTokens() as $token) {
            if ($token instanceof TextToken) {
                $textTokens[] = $token;
            } elseif ($token instanceof JavascriptTokenInterface) {
                $jsTokens[] = $token;
            }
        }

        $this->assertCount(16, $textTokens);
        $this->assertCount(1, $jsTokens);
        $this->assertEquals('jquery-2.1.0.js', (string) $jsTokens[0]);
    }

    public function testTokenizeHtml()
    {
        $relativePath = __DIR__ . '/../Assets';
        $content = file_get_contents($relativePath . '/simple.html');

        $lexer = new Lexer;
        $lexer->setRelativeFilePath($relativePath);
        $lexer->addRegex(new Regex\HtmlCssUrlRegex);
        $lexer->addRegex(new Regex\HtmlImageUrlRegex);
        $lexer->addRegex(new Regex\HtmlJavascriptUrlRegex);

        $tokenStream = $lexer->tokenize($content);

        $this->assertCount(53, $tokenStream->getTokens());

        $textTokens = [];
        $imageTokens = [];
        $cssTokens = [];
        $jsTokens = [];
        foreach ($tokenStream->getTokens() as $token) {
            if ($token instanceof TextToken) {
                $textTokens[] = $token;
            } elseif ($token instanceof ImageTokenInterface) {
                $imageTokens[] = $token;
            } elseif ($token instanceof CssTokenInterface) {
                $cssTokens[] = $token;
            } elseif ($token instanceof JavascriptTokenInterface) {
                $jsTokens[] = $token;
            }
        }

        $this->assertCount(49, $textTokens);
        $this->assertCount(2, $cssTokens);
        $this->assertCount(1, $imageTokens);
        $this->assertCount(1, $jsTokens);
        $this->assertEquals('bootstrap.css', (string) $cssTokens[0]);
        $this->assertEquals('simple.css', (string) $cssTokens[1]);
        $this->assertEquals('zoop.jpg', (string) $imageTokens[0]);
        $this->assertEquals('jquery-2.1.0.js', (string) $jsTokens[0]);
    }
}
