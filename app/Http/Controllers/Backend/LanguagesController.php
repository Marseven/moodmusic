<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 20:58
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use Illuminate\Routing\Controller;
use NiNaCoder\Translation\Drivers\Translation;
use NiNaCoder\Translation\Http\Requests\LanguageRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use NiNaCoder\Translation\Http\Requests\TranslationRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Cache;

class LanguagesController extends Controller
{
    private $translation, $request;

    public function __construct(Translation $translation, Request $request)
    {
        //$this->request = $request;
        $this->translation = $translation;
    }

    public function index()
    {

        $languages = $this->translation->allLanguages();

        return view('backend.languages.index')
            ->with('languages', $languages);
    }

    public function createLanguage(LanguageRequest $request)
    {
        $this->translation->addLanguage($request->input('locale'), ($request->input('name')));
        Cache::clear('languages');

        return redirect()->back()->with('status', 'success')->with('message', 'Language successfully added!');
    }

    public function deleteLanguage(Request $request)
    {
        if($request->route('language') == env('APP_LOCALE', 'en')) {
            abort(403, 'You can not delete the default language.');
        }

        $this->translation->deleteLanguage($request->route('language'));
        Cache::clear('languages');

        return redirect()->back()->with('status', 'success')->with('message', 'Language successfully deleted!');
    }

    public function translations(Request $request, $language)
    {
        if ($request->has('language') && $request->get('language') !== $language) {
            return redirect()
                ->route('backend.languages.translations', ['language' => $request->get('language'), 'group' => $request->get('group'), 'filter' => $request->get('filter')]);
        }

        $languages = $this->translation->allLanguages();
        $groups = $this->translation->getGroupsFor('en')->merge('missing');
        $translations = $this->translation->filterTranslationsFor($language, $request->get('filter'));

        if ($request->has('group') && $request->get('group')) {
            if ($request->get('group') === 'single') {
                $translations = $translations->get('single');
                $translations = new Collection(['single' => $translations]);
            } else if ($request->get('group') === 'missing') {
                $missingTranslations = $this->translation->findMissingTranslations($language);
                $translations = [];
                foreach ($missingTranslations['group'] as $groupKey => $group) {
                    $translations['group'][$groupKey] = $group;
                    foreach ($group as $key => $value) {
                        $translations['group'][$groupKey][$key] = array(
                            'en' => __($groupKey . '.'. $key),
                            $language => null
                        );
                    }
                }
                $translations = new Collection($translations);
            } else {
                $translations = $translations->get('group')->filter(function ($values, $group) use ($request) {
                    return $group === $request->get('group');
                });
                $translations = new Collection(['group' => $translations]);
            }
        }

        return view('backend.languages.translations.index', compact('language', 'languages', 'groups', 'translations'));
    }
    public function createTranslation(TranslationRequest $request, $language)
    {
        if ($request->input('group')) {
            $namespace = $request->has('namespace') && $request->input('namespace') ? "{$request->input('namespace')}::" : '';
            $this->translation->addGroupTranslation(env('APP_LOCALE', 'en'), "{$namespace}{$request->input('group')}", $request->input('key'), $request->input('value') ?: '');
        } else {
            $this->translation->addSingleTranslation(env('APP_LOCALE', 'en'), 'single', $request->input('key'), $request->input('value') ?: '');
        }

        return redirect()->back()->with('status', 'success')->with('message', 'Translation successfully updated!');
    }

    public function updateTranslation(Request $request, $language)
    {
        if (! Str::contains($request->input('group'), 'single')) {
            $this->translation->addGroupTranslation($language, $request->input('group'), $request->get('key'), $request->input('value') ?: '');
        } else {
            $this->translation->addSingleTranslation($language, $request->input('group'), $request->input('key'), $request->input('value') ?: '');
        }

        return response()->json(['success' => true], 200);
    }
}