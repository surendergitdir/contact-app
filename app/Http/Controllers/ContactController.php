<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Requests\Contact\CreateRequest;
use App\Http\Requests\Contact\UpdateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('id','desc')->paginate(config('constant.MAX_PAGE_SIZE'));
        return view('contacts.index', ['contacts' => $contacts]);
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(CreateRequest $request)
    {
        $existingContact = Contact::where('name', $request->name)
            ->where('phone', $request->phone)
            ->first();
        if ($existingContact) {
            return back()->withErrors('A contact with this name and phone number already exists.');
        }

        Contact::create($request->only('name', 'phone'));
        return redirect()->route('contacts.index')->with('success', 'Contact created successfully.');
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(UpdateRequest $request, Contact $contact)
    {
        $contact->update($request->only('name', 'phone'));
        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully.');
    }

    public function importXml(Request $request)
    {
        $request->validate([
            'xml_file' => 'required|file|mimes:xml,txt',
        ]);
        $xmlFilePath = $request->file('xml_file')->getRealPath();
        try {
            $xml = simplexml_load_file($xmlFilePath);
            if (!$xml || !isset($xml->contact)) {
                return back()->with('error', 'Invalid or empty XML file.');
            }
            DB::beginTransaction();
            $this->processContactsFromXml($xml);
            DB::commit();
            return redirect()->route('contacts.index')->with('success', 'Contacts imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('XML Import Error: ' . $e->getMessage());//can send email to admin of exception err
            return back()->withErrors('Failed to import contacts. Please check the XML file.');
        }
    }

    //function to save xml contact to db
    private function processContactsFromXml($xml)
    {
        foreach ($xml->contact as $item) {
            $name = (string) $item->name;
            $phone = (string) $item->phone;
            $exists = Contact::where('name', $name)
                            ->where('phone', $phone)
                            ->exists();
            if (!$exists) {
                Contact::create([
                    'name' => $name,
                    'phone' => $phone,
                ]);
            }
        }
    }
}
