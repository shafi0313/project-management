<?php

namespace App\Traits\Traits;

trait ActionTrait
{
    public function action($project)
    {
        $projectUsers = $project->load(['users:id,section_id,sub_section_id']);
        $subSectionToSectionIds = $projectUsers->users->map(function ($user) {
            return $user->subSection ? $user->subSection->section_id : null;
        })->filter()->toArray();
        $projectSectionId = array_merge($projectUsers->users->pluck('section_id')->toArray(), [1, user()->subSection?->section_id],$subSectionToSectionIds);
        $projectSubSectionId = array_merge($projectUsers->users->pluck('sub_section_id')->toArray(), [user()->sub_section_id]);
        if (!in_array(user()->section_id, $projectSectionId) || !in_array(user()->sub_section_id, $projectSubSectionId)) {
            return response()->json(['message' => 'You can not access this action'], 500);
        }
    }
}
