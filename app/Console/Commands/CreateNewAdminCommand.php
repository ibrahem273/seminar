<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateNewAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:createNewAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user['name'] = $this->ask('Name of the new Admin');
        $user['email'] = $this->ask('Email of the new Admin');
        $user['password'] = $this->ask('Password of the new Admin');
        $user['age'] = $this->ask('Age of the new Admin');
        $user['isAdmin'] = 1;

        $validator = Validator::make($user, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', \Illuminate\Validation\Rules\Password::default()],
            'age' => ['required', 'integer']

        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error)
                $this->error($error);
            return CommandAlias::INVALID;
        }
        $user['password'] = Hash::make($user['password']);
        User::create($user);
        $this->info('User ' . $user['email'] . 'created successfully');
        $this->ask('Name of the new Admin');
        return CommandAlias::SUCCESS;
    }
}
