<?php

namespace App\Validators;

use App\Services\SearchService;
use App\View\UserView;
use Framework\HTTP\Request;
use Framework\Validator;

class SearchValidator extends Validator
{
    private $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function check(Request $request)
    {
        $error = $request->check([
            ['get', 'search-string', '/^[a-zA-Z0-9\s]{3,}$/', 'Search string entered incorrectly. '.
                'it must contain at least 3 characters.'],
        ]);

        if ($error == '' && $request->exist('get', 'page')) {
            $error = $request->check([
                ['get', 'page', '/^[1-9][0-9]*$/', 'Page entered incorrectly.']
            ]);
        }

        if ($error != '') {
            return UserView::render('search', [
                'error' => $error,
                'searchString' => $this->searchService->getSearchString($request),
            ]);
        }

        return '';
    }
}
