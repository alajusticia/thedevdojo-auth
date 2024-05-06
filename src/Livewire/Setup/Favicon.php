<?php

namespace Devdojo\Auth\Livewire\Setup;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Artisan;
use Winter\LaravelConfigWriter\ArrayFile;

class Favicon extends Component
{
    use WithFileUploads;

    public $favicon_light;
    public $favicon_dark;

    public function mount(){
        $this->favicon_light = config('devdojo.auth.appearance.favicon.light');
        $this->favicon_dark = config('devdojo.auth.appearance.favicon.dark');
    }

    public function updated($property, $value){
        if($property == 'favicon_light'){
            $filename = $value->getFileName();
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            $value->storeAs('public/auth', 'favicon.' . $extension);
            $this->favicon_light = '/storage/auth/favicon.' . $extension;

            $this->updateConfigKeyValue('favicon.light', '/storage/auth/favicon.' . $extension);

            $value = null;
        }

        if($property == 'favicon_dark'){
            $filename = $value->getFileName();
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            $value->storeAs('public/auth', 'favicon-dark.' . $extension);
            $this->favicon_dark = '/storage/auth/favicon-dark.' . $extension;

            $this->updateConfigKeyValue('favicon.dark', '/storage/auth/favicon-dark.' . $extension);

            $value = null;
        }
    }

    private function updateConfigKeyValue($key, $value){
        $config = ArrayFile::open(base_path('config/devdojo/auth/appearance.php'));
        $config->set($key, $value);
        $config->write();

        Artisan::call('config:clear');

        $this->js('savedMessageOpen()');
    }

    public function render()
    {
        return view('auth::livewire.setup.favicon');
    }
}