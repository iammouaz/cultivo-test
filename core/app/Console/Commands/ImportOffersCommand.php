<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Services\OfferService;

class ImportOffersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ImportOffersCommand  {--pathCsv=} {--uuid=} {--originalFileName=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to import offers from csv file .';
    /**
     * @var OfferService
     */
    protected   $offerService;
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
         * @var OfferService $offerService
         */
        $this->offerService = app('offerService');

        $this->offerService->importOfferFromCsv($pathCsv,$uuid,$original_fileName);


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
