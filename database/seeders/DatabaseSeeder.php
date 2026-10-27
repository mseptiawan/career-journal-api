<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\WorkLog;
use App\Models\KnowledgeNote;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // tambahkan password
        ]);

        // 2. Buat Tag
        $tags = collect(['Laravel', 'Next.js', 'PostgreSQL', 'Docker'])->map(function ($name) use ($user) {
            return Tag::create([
                'user_id' => $user->id,
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        });

        // 3. Buat Project
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Career Journal App',
            'description' => 'Aplikasi untuk mencatat perjalanan karier.',
            'company_context' => 'Personal Project',
            'role' => 'Fullstack Developer',
            'started_at' => now()->subMonths(2),
            'status' => 'ongoing',
            'tech_stack' => ['Laravel', 'Next.js', 'PostgreSQL'],
        ]);

        // 4. Buat Work Log (hubungkan ke project & tags)
        $workLog = WorkLog::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'title' => 'Setup API Authentication',
            'description' => 'Mengimplementasikan Laravel Sanctum untuk autentikasi API.',
            'category' => 'coding',
            'worked_at' => now(),
            'duration_minutes' => 120,
            'status' => 'completed',
            'technologies' => ['Laravel', 'Sanctum'],
        ]);

        // Hubungkan tag ke work log (polymorphic)
        $workLog->tags()->attach($tags->whereIn('name', ['Laravel', 'PostgreSQL'])->pluck('id'));

        // 5. Buat Knowledge Note (hubungkan ke project & tags)
        $note = KnowledgeNote::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'title' => 'Memahami N+1 Query',
            'content' => 'N+1 query terjadi ketika kita memanggil relasi di dalam loop. Solusinya gunakan eager loading (with).',
            'concepts' => ['Database', 'Performance'],
        ]);

        $note->tags()->attach($tags->whereIn('name', ['Laravel'])->pluck('id'));
    }
}
