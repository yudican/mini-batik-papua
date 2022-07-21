<?php

namespace App\Http\Livewire\Banner;

use App\Models\Banner;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class BannerController extends Component
{
    use WithFileUploads;
    public $tbl_banners_id;
    public $banner_title;
    public $banner_image;
    public $banner_description;
    public $banner_image_path;


    public $route_name = false;

    public $form_active = false;
    public $form = true;
    public $update_mode = false;
    public $modal = false;

    protected $listeners = ['getDataBannerById', 'getBannerId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.banner.tbl-banners', [
            'items' => Banner::all()
        ]);
    }

    public function store()
    {
        $this->_validate();
        $banner_image = $this->banner_image_path->store('upload', 'public');
        $data = [
            'banner_title'  => $this->banner_title,
            'banner_image'  => $banner_image,
            'banner_description'  => $this->banner_description
        ];

        Banner::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'banner_title'  => $this->banner_title,
            'banner_image'  => $this->banner_image,
            'banner_description'  => $this->banner_description
        ];
        $row = Banner::find($this->tbl_banners_id);


        if ($this->banner_image_path) {
            $banner_image = $this->banner_image_path->store('upload', 'public');
            $data = ['banner_image' => $banner_image];
            if (Storage::exists('public/' . $this->banner_image)) {
                Storage::delete('public/' . $this->banner_image);
            }
        }

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        Banner::find($this->tbl_banners_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'banner_title'  => 'required',
            'banner_description'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataBannerById($tbl_banners_id)
    {
        $this->_reset();
        $tbl_banners = Banner::find($tbl_banners_id);
        $this->tbl_banners_id = $tbl_banners->id;
        $this->banner_title = $tbl_banners->banner_title;
        $this->banner_image = $tbl_banners->banner_image;
        $this->banner_description = $tbl_banners->banner_description;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getBannerId($tbl_banners_id)
    {
        $tbl_banners = Banner::find($tbl_banners_id);
        $this->tbl_banners_id = $tbl_banners->id;
    }

    public function toggleForm($form)
    {
        $this->_reset();
        $this->form_active = $form;
        $this->emit('loadForm');
    }

    public function showModal()
    {
        $this->_reset();
        $this->emit('showModal');
    }

    public function _reset()
    {
        $this->emit('closeModal');
        $this->emit('refreshTable');
        $this->tbl_banners_id = null;
        $this->banner_title = null;
        $this->banner_image = null;
        $this->banner_image_path = null;
        $this->banner_description = null;
        $this->form = true;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = false;
    }
}
