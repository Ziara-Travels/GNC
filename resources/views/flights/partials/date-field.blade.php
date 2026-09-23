{{--
    A single date-picker field: a button styled like the old native date input,
    showing a formatted date, backed by a hidden input holding the real
    YYYY-MM-DD value the backend expects. Opened/driven by
    public/js/flight-search-widget.js (window.ffInitDateField).
--}}
<button type="button"
    id="{{ $idPrefix }}_{{ $field }}_trigger"
    class="ff-date-trigger py-2 px-2.5 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-blue-500 flex items-center gap-1.5">
    <i class="fas fa-calendar-alt text-gray-300 text-[10px]"></i>
    <span class="ff-date-display text-gray-400">Select date</span>
</button>
<input type="hidden" name="{{ $name }}" id="{{ $idPrefix }}_{{ $field }}">
