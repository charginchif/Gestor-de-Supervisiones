<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\JwtService;
use App\Models\User;
use App\Models\CatRol;

class GenerateTestJwt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:test-jwt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a test JWT for a specified role.';

    protected JwtService $jwtService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(JwtService $jwtService)
    {
        parent::__construct();
        $this->jwtService = $jwtService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('--- JWT Test Token Generator ---');

        $roles = CatRol::all();

        if ($roles->isEmpty()) {
            $this->error('No roles found in the database. Please ensure the `cat_rol` table is populated.');
            return Command::FAILURE;
        }

        $this->info('Available roles:');
        foreach ($roles as $role) {
            $this->line("  - ID: {$role->id}, Name: {$role->nombre}");
        }
        $this->newLine();

        $selectedRoleId = $this->ask('Enter the ID of the role for which to generate a token (e.g., 1 for Admin):');

        $role = $roles->firstWhere('id', $selectedRoleId);

        if (!$role) {
            $this->error('Invalid role ID.');
            return Command::FAILURE;
        }

        $userId = $this->ask("Enter a dummy User ID for the token (e.g., 1, 10, 100):", 1);
        $userEmail = $this->ask("Enter a dummy User Email for the token (e.g., test@example.com):", "{$role->nombre}_{$userId}@example.com");

        $claims = [
            'sub'        => $userId,
            'usuario_id' => $userId,
            'rol'        => $role->id,
            'email'      => $userEmail,
        ];

        $token = $this->jwtService->crearToken($claims);

        $this->info("\n--- Generated JWT for Role: {$role->nombre} (ID: {$role->id}) ---");
        $this->info('Claims: ' . json_encode($claims, JSON_PRETTY_PRINT));
        $this->line("Token: {$token}");
        $this->info("\nUse this token as a Bearer token in your requests.");

        return Command::SUCCESS;
    }
}
