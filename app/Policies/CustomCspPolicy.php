<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Policy;

class CustomCspPolicy extends UsePolicy
{
    public function configure()
    {
        $this
            ->addDirective(Directive::SCRIPT, [
                Keyword::SELF,
                'https://app.sandbox.midtrans.com', // untuk sandbox
                Keyword::UNSAFE_EVAL,
            ]);
    }
}
