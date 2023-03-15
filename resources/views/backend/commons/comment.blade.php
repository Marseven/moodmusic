@foreach ($comments as $index => $comment)
    @if(isset($comment->object))
        <tr id="comment_{{ $comment->id }}">
            <td class="td-image">
                @if($comment->user)
                    <a href="{{ route('backend.users.edit', ['id' => $comment->user->id]) }}"><img src="{{ $comment->user->artwork_url }}"/></a>
                @endif
            </td>
            <td class="td-author desktop">
                @if($comment->user)
                    <a href="{{ route('backend.users.edit', ['id' => $comment->user->id]) }}">{{ $comment->user->name }}</a>
                @endif
            </td>
            <td>{{ $comment->content }}</td>
            <td class="td-object desktop"><a href="{{ $comment->object->permalink_url }}" target="_blank">{{ $comment->commentable_type }}</a></td>
            <td class="td-created-at desktop">{{ timeElapsedString($comment->created_at) }}</td>
            <td class="td-approve">
                @if(! $comment->approved)
                    <button class="btn btn-sm btn-primary" data-action="approve-comment" data-approve-uri="{{ route('backend.comments.approve') }}" data-id="{{ $comment->id }}">Approve</button>
                @else
                    <span class="badge badge-pill badge-success">approved</span>
                @endif
            </td>
            <td class="desktop">
                <a class="row-button edit" href="{{ route('backend.comments.edit', ['id' => $comment->id]) }}" data-toggle="tooltip" data-placement="left" title="Edit this comment"><i class="fas fa-fw fa-edit"></i></a>
                <a class="row-button delete"  href="{{ route('backend.comments.delete', ['id' => $comment->id]) }}" onclick="return confirm('Are you sure want to delete this comment?')" data-toggle="tooltip" data-placement="left" title="Delete this comment"><i class="fas fa-fw fa-trash"></i></a>
            </td>
        </tr>
    @endif
@endforeach