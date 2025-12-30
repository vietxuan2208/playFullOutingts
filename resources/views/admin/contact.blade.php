@extends('admin.dashboard')
@section('page-title', 'Contact')

@section('content')
<style>
tbody tr:hover { background:#f1f5f9; cursor:pointer }
td pre { white-space:pre-wrap; margin:0 }
</style>

<div class="card mt-4">
    <div class="card-header">
        <h5>Contact Messages</h5>
    </div>

    <div class="card-body">

        <!-- FILTER + SEARCH -->
        <div class="row g-2 mb-3">
            <div class="col-auto">
                <select class="form-select" id="searchStatus">
                    <option value="">All</option>
                    <option value="1">Read</option>
                    <option value="0">Unread</option>
                </select>
            </div>
            <div class="col-auto ms-auto">
                <input id="searchInput" class="form-control" placeholder="Search name, email, message...">
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered" id="messageTable">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Received</th>
                        <th>Status</th>
                        <th>Reply</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($messages as $msg)
                    <tr data-id="{{ $msg->id }}" class="{{ !$msg->read ? 'table-warning' : '' }}">
                        <td>{{ $msg->name }}</td>
                        <td>{{ $msg->email }}</td>
                        <td><pre>{{ $msg->message }}</pre></td>
                        <td>{{ $msg->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <span class="badge {{ $msg->read ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $msg->read ? 'Read' : 'Unread' }}
                            </span>
                        </td>
                        <td>
                            {!! $msg->reply
                                ? '<pre class="text-muted mb-0">'.$msg->reply.'</pre>'
                                : '<span class="text-muted">Not replied</span>' !!}
                        </td>
                        <td>
                            @if(!$msg->reply)
                                <button class="btn btn-sm btn-outline-success replyBtn"
                                    data-id="{{ $msg->id }}"
                                    data-name="{{ $msg->name }}">
                                    Reply
                                </button>
                            @else
                                <span class="badge bg-secondary">Replied</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3" id="pagination"></div>
    </div>
</div>

<!-- VIEW MODAL -->
<div class="modal fade" id="viewMessageModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<div class="modal-header bg-primary text-white">
    <h5>Message Details</h5>
    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <p><b>Name:</b> <span id="viewName"></span></p>
    <p><b>Email:</b> <span id="viewEmail"></span></p>
    <p><b>Message:</b></p>
    <pre id="viewMessage"></pre>
    <p><b>Reply:</b></p>
    <pre id="viewReply"></pre>
</div>
</div>
</div>
</div>

<!-- REPLY MODAL -->
<div class="modal fade" id="replyModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <h5>Reply</h5>
    <button class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <b>To:</b> <span id="replyTo"></span>
    <textarea id="replyText" class="form-control mt-2" rows="5"></textarea>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button class="btn btn-primary" id="sendReplyBtn">Send</button>
</div>
</div>
</div>
</div>

<script>
const rowsPerPage = 10;
let currentPage = 1;
let filteredRows = [];
let replyId = null;

function filterRows() {
    const txt = searchInput.value.toLowerCase();
    const st = searchStatus.value;

    const rows = [...document.querySelectorAll("#messageTable tbody tr")];

    filteredRows = rows.filter(r => {
        const text = r.innerText.toLowerCase();
        const status = r.querySelector("td:nth-child(5)").innerText.includes("Read") ? "1" : "0";
        return text.includes(txt) && (st === "" || st === status);
    });

    currentPage = 1;
    render();
}

function render() {
    document.querySelectorAll("#messageTable tbody tr").forEach(r => r.style.display="none");
    filteredRows
        .slice((currentPage-1)*rowsPerPage, currentPage*rowsPerPage)
        .forEach(r => r.style.display="");

    pagination.innerHTML="";
    const pages = Math.ceil(filteredRows.length / rowsPerPage);
    for(let i=1;i<=pages;i++){
        pagination.innerHTML += `
            <button class="btn btn-sm btn-outline-primary mx-1 ${i===currentPage?'active':''}"
                onclick="currentPage=${i};render()">${i}</button>`;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    filterRows();
    searchInput.oninput = filterRows;
    searchStatus.onchange = filterRows;

    document.querySelectorAll(".replyBtn").forEach(b=>{
        b.onclick=e=>{
            e.stopPropagation();
            replyId=b.dataset.id;
            replyTo.innerText=b.dataset.name;
            replyText.value="";
            new bootstrap.Modal(replyModal).show();
        }
    });

    sendReplyBtn.onclick=()=>{
        fetch(`/admin/contact/${replyId}/reply`,{
            method:"POST",
            headers:{
                "Content-Type":"application/json",
                "X-CSRF-TOKEN":"{{ csrf_token() }}"
            },
            body:JSON.stringify({reply:replyText.value})
        }).then(()=>{
            const row = document.querySelector(`tr[data-id="${replyId}"]`);
            row.querySelector("td:nth-child(6)").innerHTML =
                `<pre class="text-muted mb-0">${replyText.value}</pre>`;
            row.querySelector("td:nth-child(7)").innerHTML =
                `<span class="badge bg-secondary">Replied</span>`;
            bootstrap.Modal.getInstance(replyModal).hide();
        });
    };

    document.querySelectorAll("#messageTable tbody tr").forEach(r=>{
        r.onclick=()=>{
            viewName.innerText=r.cells[0].innerText;
            viewEmail.innerText=r.cells[1].innerText;
            viewMessage.innerText=r.cells[2].innerText;
            viewReply.innerText=r.cells[5].innerText;
            new bootstrap.Modal(viewMessageModal).show();
        }
    });
});
</script>
@endsection
