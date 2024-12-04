<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TimesheetController extends Controller
{
    /**
     * Display a list of tasks with filtering, sorting, and pagination options.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $validRequest = $request->validate([
            'limit' => 'integer|min:1|max:100',
            'dateRange' => 'array|nullable',
            'sort' => 'array|nullable',
        ]);

        // Map user IDs to their names for displaying in the view.
        $userMap = User::pluck('name', 'id')->toArray();

        // Set default values if not provided in the request.
        $limit = $validRequest['limit'] ?? 10;
        $dateRange = $validRequest['dateRange'] ?? [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
        $sort = $validRequest['sort'] ?? null;

        // Query the tasks within the specified date range and limit the results.
        $tasksQuery = Task::whereBetween('created_at', [$dateRange[0], $dateRange[1]])
            ->take($limit)
            ->with('users'); // Eager load related users.

        // Apply user filter if a user ID is provided in the sort parameters.
        if (isset($sort['user'])) {
            $tasksQuery->whereHas('users', function ($query) use ($sort) {
                $query->where('users.id', $sort['user']);
            });
        }

        // Fetch the tasks from the database.
        $tasks = $tasksQuery->get();

        return Inertia::render('Dashboard', [
            'tasks' => fn() => $tasks,
            'userMap' => $userMap,
            'sort' => $sort,
            'dateRange' => $dateRange,
        ]);
    }

    /**
     * Create a new task based on user input.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string|max:255',
        ]);

        Task::create($validated);

        return back();
    }

    /**
     * Update an existing task with new data.
     *
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'created_at' => 'date',
            'expired_at' => 'date|nullable',
        ]);

        $updated = $task->update($validated);

        return response()->json(['success' => $updated]);
    }

    /**
     * Attach the authenticated user to the specified task.
     *
     * @param Request $request
     * @param Task $task
     * @return RedirectResponse
     */
    public function join(Request $request, Task $task): RedirectResponse
    {
        // Attach the current user to the task.
        $task->users()->attach($request->user());
        $task->save();

        return back();
    }

    /**
     * Detach the authenticated user from the specified task.
     *
     * @param Request $request
     * @param Task $task
     * @return RedirectResponse
     */
    public function leave(Request $request, Task $task): RedirectResponse
    {
        // Detach the current user from the task.
        $task->users()->detach($request->user());
        $task->save();

        return back();
    }

    /**
     * Delete the specified task and detach all associated users.
     *
     * @param Task $task
     * @return RedirectResponse
     */
    public function delete(Task $task): RedirectResponse
    {
        // Detach all users from the task before deletion.
        $task->users()->detach();
        $task->save();

        // Delete the task from the database.
        $task->delete();

        return back();
    }
}
