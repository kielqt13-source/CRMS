<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recognition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'batch_id',
        'document_type_id',
        'file_path',
        'original_filename',
        'file_type',
        'status',
        'recognized_text',
        'confidence',
        'api_response',
        'verified_by',
        'verified_at',
        'rejection_reason',
    ];

    protected $casts = [
        'api_response'     => 'array',
        'extracted_fields' => 'array',
        'corrected_fields' => 'array',
        'confidence'       => 'decimal:2',
        'verified_at'      => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Returns the template field definitions for a given document type.
     */
    public static function getDocumentFields(string $documentType): array
    {
        return match ($documentType) {
            'Birth Certificate' => [
                'registry_number'     => 'Registry Number',
                'child_first_name'    => "Child's First Name",
                'child_middle_name'   => "Child's Middle Name",
                'child_last_name'     => "Child's Last Name",
                'sex'                 => 'Sex',
                'date_of_birth'       => 'Date of Birth',
                'place_of_birth_city' => 'City / Municipality of Birth',
                'place_of_birth_prov' => 'Province of Birth',
                'father_first_name'   => "Father's First Name",
                'father_middle_name'  => "Father's Middle Name",
                'father_last_name'    => "Father's Last Name",
                'mother_first_name'   => "Mother's First Name",
                'mother_middle_name'  => "Mother's Middle Name",
                'mother_last_name'    => "Mother's Last Name",
                'date_registered'     => 'Date Registered',
            ],
            'Marriage Certificate' => [
                'registry_number'    => 'Registry Number',
                'husband_first_name' => "Husband's First Name",
                'husband_middle_name'=> "Husband's Middle Name",
                'husband_last_name'  => "Husband's Last Name",
                'wife_first_name'    => "Wife's First Name",
                'wife_middle_name'   => "Wife's Middle Name",
                'wife_last_name'     => "Wife's Last Name",
                'date_of_marriage'   => 'Date of Marriage',
                'place_of_marriage'  => 'Place of Marriage',
                'date_registered'    => 'Date Registered',
            ],
            'Death Certificate' => [
                'registry_number'      => 'Registry Number',
                'deceased_first_name'  => "Deceased's First Name",
                'deceased_middle_name' => "Deceased's Middle Name",
                'deceased_last_name'   => "Deceased's Last Name",
                'sex'                  => 'Sex',
                'age'                  => 'Age',
                'date_of_death'        => 'Date of Death',
                'place_of_death'       => 'Place of Death',
                'cause_of_death'       => 'Cause of Death',
                'date_registered'      => 'Date Registered',
            ],
            default => [],
        };
    }

    /**
     * Badge colour for a given status.
     */
    public static function statusBadge(string $status): string
    {
        return match ($status) {
            'completed'  => 'info',
            'verified'   => 'success',
            'rejected'   => 'danger',
            'processing' => 'primary',
            'failed'     => 'danger',
            default      => 'warning', // pending
        };
    }
}
