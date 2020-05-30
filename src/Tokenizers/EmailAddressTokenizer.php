<?php

namespace Permafrost\TextClassifier\Tokenizers;

use Permafrost\TextClassifier\Utilities\NGram;

class EmailAddressTokenizer implements Tokenizer
{
    public function tokenize(string $text): array
    {
        [$alias, $domain] = array_pad(explode('@', trim($text), 2), 2, '');

        $domainParts = explode('.', strtolower(idn_to_ascii($domain)));
        $aliasTrigrams = NGram::for($alias, 3);
        $domainTrigrams = NGram::for(($domainParts[0] ?? ''), 3);

        //return trigrams for both alias and domain, as well as domain tokens ('gmail', 'com'), ('bbc','co','uk'), etc.
        return array_merge($aliasTrigrams, $domainTrigrams, $domainParts);
    }
}
