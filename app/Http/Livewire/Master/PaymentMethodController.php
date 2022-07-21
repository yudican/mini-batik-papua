<?php

namespace App\Http\Livewire\Master;

use App\Models\PaymentMethod;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class PaymentMethodController extends Component
{
    use WithFileUploads;
    public $tbl_payment_method_id;
    public $nama_bank;
public $logo_bank;
public $nomor_rekening_bank;
public $nama_rekening_bank;
    public $logo_bank_path;
   

    public $route_name = false;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataPaymentMethodById', 'getPaymentMethodId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.master.tbl-payment-method', [
            'items' => PaymentMethod::all()
        ]);
    }

    public function store()
    {
        $this->_validate();
        $logo_bank = $this->logo_bank_path->store('upload', 'public');
        $data = ['nama_bank'  => $this->nama_bank,
'logo_bank'  => $logo_bank,
'nomor_rekening_bank'  => $this->nomor_rekening_bank,
'nama_rekening_bank'  => $this->nama_rekening_bank];

        PaymentMethod::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = ['nama_bank'  => $this->nama_bank,
'logo_bank'  => $this->logo_bank,
'nomor_rekening_bank'  => $this->nomor_rekening_bank,
'nama_rekening_bank'  => $this->nama_rekening_bank];
        $row = PaymentMethod::find($this->tbl_payment_method_id);

        
                    if ($this->logo_bank_path) {
                        $logo_bank = $this->logo_bank_path->store('upload', 'public');
                        $data = ['logo_bank' => $logo_bank];
                        if (Storage::exists('public/' . $this->logo_bank)) {
                            Storage::delete('public/' . $this->logo_bank);
                        }
                    }

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        PaymentMethod::find($this->tbl_payment_method_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'nama_bank'  => 'required',
'nomor_rekening_bank'  => 'required',
'nama_rekening_bank'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataPaymentMethodById($tbl_payment_method_id)
    {
        $this->_reset();
        $tbl_payment_method = PaymentMethod::find($tbl_payment_method_id);
        $this->tbl_payment_method_id = $tbl_payment_method->id;
        $this->nama_bank = $tbl_payment_method->nama_bank;
$this->logo_bank = $tbl_payment_method->logo_bank;
$this->nomor_rekening_bank = $tbl_payment_method->nomor_rekening_bank;
$this->nama_rekening_bank = $tbl_payment_method->nama_rekening_bank;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getPaymentMethodId($tbl_payment_method_id)
    {
        $tbl_payment_method = PaymentMethod::find($tbl_payment_method_id);
        $this->tbl_payment_method_id = $tbl_payment_method->id;
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
        $this->tbl_payment_method_id = null;
        $this->nama_bank = null;
$this->logo_bank = null;
$this->nomor_rekening_bank = null;
$this->nama_rekening_bank = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
