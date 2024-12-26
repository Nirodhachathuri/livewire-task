<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'tasks';
    
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
    ];

    /**
     * The attributes that should be cast to a native type.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Get the status attribute.
     *
     * @return string
     */
    public function getStatusAttribute($value)
    {
        return ucfirst($value); // Capitalizes the status
    }

    /**
     * Set the status attribute.
     *
     * @param string $value
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtolower($value); // Ensures status is always stored in lowercase
    }

    /**
     * Get the task's creation date.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function createdAt()
    {
        return $this->created_at;
    }

    /**
     * Get the task's update date.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function updatedAt()
    {
        return $this->updated_at;
    }
}
