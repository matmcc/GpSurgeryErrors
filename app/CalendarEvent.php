<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MaddHatter\LaravelFullcalendar\IdentifiableEvent;
use Carbon\Carbon;


class CalendarEvent extends Model implements IdentifiableEvent
{

    protected $dates = ['start', 'end'];
    protected $attributes = ['is_all_day' => 0];
    protected $fillable = ['event_name', 'user_id', 'admin_id', 'start_datetime', 'end_datetime', 'is_all_day'];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    //TODO: Build from array keys in constructor?
    protected $appends = ['color', 'overlap', 'startEditable',
        'durationEditable', 'url', 'rendering'];

    // IdentifiableEvent implementation
    // -----------------------------------------------------------------------------------------

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay()
    {
        //return $this->is_all_day;
        return false;
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Get the event's ID
     *
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }

    // END IdentifiableEvent implementation
    // -----------------------------------------------------------------------------------------

    /**
     * Optional FullCalendar.io settings for this event
     *
     * color: event's background & border colour
     * overlap: false -> events cannot overlap
     * startEditable: true -> can edit start time
     * durationEditable: true if admin else false
     * url: route to events.show
     *
     * @return array
     */
    public function getEventOptions()
    {
        if (Auth::guard('admin')->check()) {
            $user = Auth::user();
            if ($user->job_title == 'admin') {
                return [
                    'color' => $this->background_color,
                    'overlap' => false,
                    'startEditable' => true,
                    'durationEditable' => true,
                    'url' => route('calendar_events.show', $this->id),
                ];
            }
            else {
                return [
                    'color' => $this->eventBelongsToAdmin() ? $this->background_color : '#ff9f89',
                    'overlap' => false,
                    'startEditable' => $this->eventBelongsToAdmin(),
                    'durationEditable' => $this->eventBelongsToAdmin(),
                    'url' => route('calendar_events.show', $this->id),
                    'rendering' => $this->eventBelongsToAdmin() ? '' : 'background',
                ];
                }
        }
        else if (Auth::check()) {
            return [
                'color' => $this->eventBelongsToUser() ? $this->background_color : '#ff9f89',
                'overlap' => false,
                'startEditable' => $this->eventBelongsToUser(),
                'durationEditable' => false,
                'url' => route('calendar_events.show', $this->id),
                'rendering' => $this->eventBelongsToUser() ? '' : 'background',
                // 'className' => something to set style per dr ?
            ];
        }
        else {
            return [
                'color' => $this->eventBelongsToUser() ? $this->background_color : '#ff9f89',
                'overlap' => false,
                'startEditable' => $this->eventBelongsToUser(),
                'durationEditable' => false,
                'url' => route('calendar_events.show', $this->id),
                'rendering' => $this->eventBelongsToUser() ? '' : 'background',
                // 'className' => something to set style per dr ?
            ];
        }
    }

    // -----------------------------------------------------------------------------------------
    // Accessor's to append Event options to JSON

    public function getColorAttribute()
    {
        return $this->getEventOptions()['color'];
    }

    public function getOverlapAttribute()
    {
        return $this->getEventOptions()['overlap'];
    }

    public function getStartEditableAttribute()
    {
        return $this->getEventOptions()['startEditable'];
    }

    public function getDurationEditableAttribute()
    {
        return $this->getEventOptions()['durationEditable'];
    }

    public function getUrlAttribute()
    {
        return $this->getEventOptions()['url'];
    }

    public function getRenderingAttribute()
    {
        return $this->getEventOptions()['rendering'];
    }

    // -----------------------------------------------------------------------------------------

    // DB relationships

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function admin()
    {
        return $this->belongsTo('App\Admin');
    }

    public function eventBelongsToUser()
    {
        return Auth::id() == $this->user()->first()->id;
    }

    public function eventBelongsToAdmin()
    {
        return Auth::id() == $this->admin()->first()->id;
    }

    public function scopeBetween($query, Carbon $start, Carbon $end)
    {
        return $query->where('start', '<=', $end)->where('end', '>', $start);
    }

    public function scopeClash($query, CalendarEvent $event)
    {
        return $query->where('start', '<=', $event->end)->where('end', '>', $event->start);
    }

}
