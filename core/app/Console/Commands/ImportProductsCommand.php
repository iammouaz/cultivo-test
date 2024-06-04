<?php

namespace App\Console\Commands;

use App\Http\Helpers\ImportProductHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Services\ProductService;

class ImportProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ImportProductsCommand  {--pathCsv=} {--uuid=} {--originalFileName=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to import products from csv file .';
    /**
     * @var ProductService
     */
    protected   $productService;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // get parameters from Artisan::call($commandName, $parameters);
        $pathCsv = $this->option('pathCsv');
        $uuid = $this->option('uuid');
        $original_fileName = $this->option('originalFileName');
        /*
         * @var ProductService $productService
         */
        $this->productService = app('productService');

        $this->productService->importProductFromCsv($pathCsv,$uuid,$original_fileName);


    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            [   'pathCsv', InputArgument::REQUIRED, 'The path of csv file.'],
            [   'uuid', InputArgument::REQUIRED, 'The uuid.'],
            [   'originalFileName', InputArgument::REQUIRED, 'The file.',],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            [   'pathCsv', null, InputOption::VALUE_REQUIRED, 'The path of csv file.', null],
            [   'uuid', null, InputOption::VALUE_REQUIRED, 'The uuid.', null],
            [   'originalFileName', null, InputOption::VALUE_REQUIRED, 'The file.', null],
        ];
    }
}
