@include('admin.project.css')
<div class="tab-pane fade show active mt-2" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab"
    tabindex="0">
    <div class="row">
        <div class="col-md-8">
            <div class="cust_card">
                <span class="fs-3 me-2">{{ $project->name }}</span>
                {!! projectStatus($project->status) !!}
                <hr>
                <div class="row ">
                    @php
                        $totalTasks = $project->tasks->count();
                        $completedTasks = $project->tasks->where('status', 3)->count();

                        if ($totalTasks > 0) {
                            $percentage = ($completedTasks / $totalTasks) * 100;
                        } else {
                            $percentage = 0;
                        }
                        if ($percentage < 20) {
                            $bg = 'bg-danger';
                        } elseif ($percentage < 40) {
                            $bg = 'bg-warning';
                        } elseif ($percentage < 60) {
                            $bg = 'bg-info';
                        } elseif ($percentage < 80) {
                            $bg = 'bg-primary';
                        } else {
                            $bg = 'bg-success';
                        }
                    @endphp
                    <div class="col-md-3">
                        <label class="fw-bold">Progress:</label>
                        <div class="progress" role="progressbar" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar {{ $bg }}" style="width:{{ $percentage }}%">{{ $percentage }}%</div>
                        </div>
                    </div>
                    <div class="col">
                        <label class="fw-bold">Created On:</label>
                        <p>{{ Carbon\Carbon::parse($project->created_at)->diffForHumans() }}</p>
                    </div>
                    <div class="col">
                        <label class="fw-bold">Last Updated:</label>
                        <p>{{ Carbon\Carbon::parse($project->updated_at)->diffForHumans() }}</p>
                    </div>

                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label class="fw-bold">Job Description:</label><br>
                        {!! $project->job_description !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="cust_card">
                    <div class="col-md-12">
                        <label class="fw-bold">Created By</label>
                        <p>{{ $project->createdBy->name }}</p>
                    </div>
                    <div class="col-md-12">
                        <label class="fw-bold">Last Update By</label>
                        <p>{{ $project->updatedBy->name }}</p>
                    </div>
                    <div class="col-md-12">
                        <label class="fw-bold">Project Members</label>
                        <ul class="member">
                            @foreach ($project->users as $user)
                                <li class="d-flex align-items-center">
                                    <div>
                                        <img src="{{ imagePath('user', $user->image) }}" alt="">
                                    </div>
                                    <div>
                                        <span class="fw-bold">{{ $user->name }}</span>
                                        <br>
                                        <span>{{ $user->email }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
