<?php

namespace App\View\Composers;

use App\Models\Company;
use Illuminate\View\View;

class FooterComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Get the primary/active company or first company
        $company = Company::where('is_active', true)->first() ?? Company::first();
        
        $view->with('footerCompany', $company);
    }
}
