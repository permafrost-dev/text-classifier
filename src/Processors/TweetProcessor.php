<?php

namespace Permafrost\TextClassifier\Processors;

class TweetProcessor extends BasicTextNormalizer
{
    protected function replaceUrls(string $text): string
    {
        return preg_replace(
            [
                '~(https?://)?pic\.twitter\.com/\w+~',
                '~(https?://www\.|https?://|www\.)[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+/?([/a-zA-Z0-9\-\._]+)?~',
            ],
            '--URL--',
            $text
        );
    }

    protected function replaceMentions(string $text): string
    {
        return preg_replace('~@[a-zA-Z0-9_]+~', '--MENTION--', $text);
    }

    protected function convertHashtags(string $text): string
    {
        return preg_replace('~#([a-zA-Z0-9]+)~', '$1', $text);
    }

    protected function removeRetweet(string $text): string
    {
        return preg_replace('~\brt\b~', '', $text);
    }

    protected function removeRepeatedDots(string $text): string
    {
        return preg_replace(['~\.{2,}~', '…'], ' ', $text);
    }

    protected function replaceHtmlEntities(string $text): string
    {
        return str_replace('&amp;', '&', $text);
    }

    protected function replaceTextEmoi(string $text): string
    {
        $result = $text;

        //Smile -- :), : ), :-), (:, ( :, (-:, :')
        //Laugh -- :D, : D, :-D, xD, x-D, XD, X-D
        //Love -- <3, :*
        //Wink -- ;-), ;), ;-D, ;D, (;,  (-;
        $result = preg_replace(
            [
            '~(:\s?\)|:-\)|\(\s?:|\(-:|:\'\))~', '~(:\s?D|:-D|x-?D|X-?D)~', '~(<3|:\*)~', '~(;-?\)|;-?D|\(-?;)~', ],
            ' --EMO_POS-- ',
            $result
        );

        //Sad -- :-(, : (, :(, ):, )-:
        //Cry -- :,(, :'(, :"(
        $result = preg_replace(
            [
            '~(:\s?\(|:-\(|\)\s?:|\)-:)~', '~(:,\(|:\'\(|:"\()~', ],
            ' --EMO_NEG-- ',
            $result
        );

        return $result;
    }

    public function process(string $text): string
    {
        $result = $text;

        $result = $this->replaceHtmlEntities($result);
        $result = parent::process($result);
        $result = $this->replaceTextEmoi($result);
        $result = $this->replaceUrls($result);
        $result = $this->replaceMentions($result);
        $result = $this->convertHashtags($result);
        $result = $this->removeRetweet($result);
        $result = $this->removeRepeatedDots($result);

        return $result;
    }
}
