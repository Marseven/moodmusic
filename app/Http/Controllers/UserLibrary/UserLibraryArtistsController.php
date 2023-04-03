<?php namespace App\Http\Controllers\UserLibrary;

use App\User;
use Auth;
use Common\Core\BaseController;
use Common\Database\Paginator;
use Illuminate\Http\Request;

class UserLibraryArtistsController extends BaseController {

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(User $user = null)
    {
        $user = $user ?? Auth::user();
        $this->authorize('show', $user);

        $paginator = (new Paginator($user->likedArtists(), $this->request->all()));

        // TODO: if order col created_at order by likes.created_at

        $paginator->defaultPerPage = 25;
        $paginator->setDefaultOrderColumns('likes.created_at', 'desc');

        $paginator->searchCallback = function($builder, $query) {
            $builder->where('name', 'LIKE', $query.'%');
        };

        $pagination = $paginator->paginate();

        if ($pagination->first() && $pagination->first()['model_type'] === User::class) {
            $pagination->transform(function(User $artist) {
                $artist->setGravatarSize(220);
                return $artist;
            });
        }

        return $this->success(['pagination' => $pagination]);
    }
}
