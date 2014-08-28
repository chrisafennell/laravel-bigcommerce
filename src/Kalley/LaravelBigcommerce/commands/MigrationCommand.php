<?php
/**
 * Command to ease the migrations publishing
 *
 * @package   kalley/laravel-bigcommerce
 * @author    Kalley Powell <kalley.powell@gmail.com>
 * @copyright Copyright (c) Kalley Powell
 * @licence   http://mit-license.org/
 * @link      https://github.com/kalley\laravel-bigcommerce
 */

namespace Kalley\LaravelBigcommerce\Console;

use Illuminate\Console\Command;

class MigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bigcommerce:migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the migrations needed for Bigcommerce';

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('table', null, InputOption::VALUE_OPTIONAL, 'Table name.', 'users'),
        );
    }

    /**
     * Create a new reminder table command instance.
     *
     * @return \LucaDegasperi\OAuth2Server\Console\MigrationsCommand
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $app = app();
        // Prepare variables
        $table = lcfirst($this->option('table'));

        $viewVars = compact(
            'table'
        );

        // Prompt
        $this->line('');
        $this->info("Table name: $table");
        $this->comment(
            "A migration that creates the $table table will".
            " be created in app/database/migrations directory"
        );
        $this->line('');

        if ($this->confirm("Proceed with the migration creation? [Yes|no]")) {
            $this->info("Creating migration...");
            // Generate
            $filename = 'database/migrations/'.
                date('Y_m_d_His')."_add_bigcommerce_to_users_table.php";
            $output = $this->app['view']->make('laravel-bigcommerce::generators.migration', $viewVars)
              ->render();
            $filename = $this->app['path'].'/'.trim($filename,'/');
            $directory = dirname($filename);
            if (!is_dir($directory)) {
                @mkdir($directory, 0755, true);
            }
            @file_put_contents($filename, $output, FILE_APPEND);

            $this->info("Migration successfully created!");
        }
        $this->call('dump-autoload');
    }
}
