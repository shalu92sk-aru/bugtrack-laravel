@extends('layouts.app')
@section('title','Dashboard')@section('heading','Dashboard')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div><h1 class="page-title mb-1">Good day, {{explode(' ',auth()->user()->name)[0]}}</h1><div class="muted">Here's what's happening across your bug reports.</div></div><a href="{{route('bugs.create')}}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Report a bug</a></div>
<div class="row g-3 mb-4">
@foreach([['Total bugs',$stats['total'],'bi-bug','primary'],['Open',$stats['open'],'bi-inbox','warning'],['In progress',$stats['progress'],'bi-arrow-repeat','info'],['Resolved',$stats['resolved'],'bi-check-circle','success'],['Critical active',$stats['critical'],'bi-exclamation-triangle','danger']] as $s)
<div class="col-6 col-xl"><div class="card p-3 h-100"><div class="d-flex justify-content-between"><span class="muted">{{$s[0]}}</span><i class="bi {{$s[2]}} text-{{$s[3]}} fs-5"></i></div><div class="stat mt-2">{{$s[1]}}</div></div></div>
@endforeach
</div>
<div class="card"><div class="p-3 border-bottom d-flex justify-content-between align-items-center"><div class="fw-bold">Recent bug reports</div><a href="{{route('bugs.index')}}" class="text-decoration-none">View all <i class="bi bi-arrow-right"></i></a></div>
<div class="table-responsive"><table class="table mb-0"><thead><tr><th>Issue</th><th>Project</th><th>Severity</th><th>Status</th><th>Assignee</th></tr></thead><tbody>
@forelse($bugs as $bug)<tr><td><a class="fw-semibold text-decoration-none" href="{{route('bugs.show',$bug)}}">{{$bug->bug_key}} · {{$bug->title}}</a></td><td>{{$bug->project->name}}</td><td><span class="badge-soft bg-{{['critical'=>'danger','high'=>'warning','medium'=>'info','low'=>'secondary'][$bug->severity]}}-subtle text-{{['critical'=>'danger','high'=>'warning','medium'=>'info','low'=>'secondary'][$bug->severity]}} text-capitalize">{{$bug->severity}}</span></td><td><span class="text-capitalize">{{$bug->status==='in_progress'?'In progress':$bug->status}}</span></td><td>{{$bug->assignee?->name ?? 'Unassigned'}}</td></tr>@empty<tr><td colspan="5" class="text-center muted py-4">No bug reports yet.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
