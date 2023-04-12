<?php

namespace KY\AdminPanel\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use KY\AdminPanel\Facades\APMedia;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;
class ToolsController extends Controller
{

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var LaravelLogViewer
     */
    private $logViewer;

    public function __construct()
    {
//        $this->authorize('view_tools');
        $this->logViewer = new LaravelLogViewer();
        $this->request = app('request');
    }

    public function index()
    {
        return view('adminpanel::tools.index');
    }

    public function controlPanel()
    {
        return view('adminpanel::tools.control-panel');
    }

    public function commands(Request $request)
    {
        $artisanOutput = '';

        if ($request->isMethod('post')) {
            $command = $request->command;
            $args = $request->args;
            $args = (isset($args)) ? ' '.$args : '';

            try {
                Artisan::call($command.$args);
                $artisanOutput = Artisan::output();
            } catch (Exception $e) {
                $artisanOutput = $e->getMessage();
            }
        }

        // get the full list of artisan commands and store the output
        $commands = $this->getArtisanCommands();

        return view('adminpanel::tools.commands',compact('commands','artisanOutput'));
    }

    public function logs(Request $request)
    {

        if ($this->request->input('log')) {
            $this->logViewer->setFile(base64_decode($this->request->input('log')));
        }

        if ($this->request->input('download')) {

            return $this->download($this->logViewer->pathToLogFile(base64_decode($this->request->input('download'))));
        } elseif ($this->request->has('del')) {
            app('files')->delete($this->logViewer->pathToLogFile(base64_decode($this->request->input('del'))));

            return redirect($this->request->url().'?logs=true')->with([
                'message'    => __('voyager::compass.logs.delete_success').' '.base64_decode($this->request->input('del')),
                'alert-type' => 'success',
            ]);
        } elseif ($this->request->has('delall')) {
            foreach ($this->logViewer->getFiles(true) as $file) {
                app('files')->delete($this->logViewer->pathToLogFile($file));
            }
            return redirect($this->request->url().'?logs=true')->with([
                'message'    => __('voyager::compass.logs.delete_all_success'),
                'alert-type' => 'success',
            ]);
        }


        $logs = $this->logViewer->all();
        $files = $this->logViewer->getFiles(true);
        $currentFile = $this->logViewer->getFileName();

        return view('adminpanel::tools.logs',compact('logs', 'files', 'currentFile'));
    }

    private function download($data)
    {
        return response()->download($data);
    }

    private function getArtisanCommands()
    {
        Artisan::call('list');

        // Get the output from the previous command
        $artisan_output = Artisan::output();
        $artisan_output = $this->cleanArtisanOutput($artisan_output);
        $commands = $this->getCommandsFromOutput($artisan_output);

        return $commands;
    }

    private function cleanArtisanOutput($output)
    {

        // Add each new line to an array item and strip out any empty items
        $output = array_filter(explode("\n", $output));

        // Get the current index of: "Available commands:"
        $index = array_search('Available commands:', $output);

        // Remove all commands that precede "Available commands:", and remove that
        // Element itself -1 for offset zero and -1 for the previous index (equals -2)
        $output = array_slice($output, $index - 2, count($output));

        return $output;
    }

    private function getCommandsFromOutput($output)
    {
        $commands = [];

        foreach ($output as $output_line) {
            if (empty(trim(substr($output_line, 0, 2)))) {
                $parts = preg_split('/  +/', trim($output_line));
                $command = (object) ['name' => trim(@$parts[0]), 'description' => trim(@$parts[1])];
                array_push($commands, $command);
            }
        }

        return $commands;
    }
}
