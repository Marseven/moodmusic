<?php namespace App\Http\Controllers\UserLibrary;

use App\Track;
use App\User;
use Auth;
use Common\Database\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Common\Core\BaseController;

class UserLibraryAlbumsController extends BaseController {

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->middleware('auth');

        $this->request = $request;
    }

    public function index(User $user = null)
    {
        $user = $user ?? Auth::user();

        $this->authorize('show', $user);

        $paginator = (new Paginator($user->likedAlbums(), $this->request->all()))
            ->with('artists');

        $paginator->searchCallback = function(MorphToMany $builder, $query) {
            $builder->where('name', 'LIKE', $query.'%');
            $builder->orWhereHas('artists', function(Builder $q) use($query) {
                return $q->where('name', 'LIKE', $query.'%');
            });
        };

        $paginator->defaultPerPage = 25;
        $paginator->setDefaultOrderColumns('likes.created_at', 'desc');
        $pagination = $paginator->paginate();

        return $this->success(['pagination' => $pagination]);
    }
}
