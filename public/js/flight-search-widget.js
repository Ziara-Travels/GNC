/**
 * Shared behaviour for the flight search widget (resources/views/flights/partials/search-widget.blade.php).
 * One widget instance per page is expected; everything is scoped by `idPrefix`
 * so the same script safely drives both the homepage hero widget and the
 * dedicated /flights/search page without id collisions.
 *
 * Plain vanilla JS on purpose: this project loads Alpine.js as a package.json
 * dependency but never actually includes it on any page, and every other
 * piece of search-form JS in this codebase (autocomplete, tab switching) is
 * already vanilla — adding a framework here just for this widget would be a
 * new, inconsistent dependency rather than a reuse of an existing one.
 */
(function () {
    'use strict';

    var MAX_LEGS = 6;
    var MIN_LEGS = 2;
    var MONTH_NAMES = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var DAY_NAMES = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];

    // One shared calendar popover + airport-search cache per widget instance.
    var widgets = {};

    function pad(n) { return n < 10 ? '0' + n : '' + n; }
    function toISO(date) { return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate()); }
    function toDisplay(date) {
        return ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][date.getDay()] + ', ' + date.getDate() + ' ' + MONTH_NAMES[date.getMonth()].slice(0, 3);
    }
    function parseISO(iso) {
        if (!iso) return null;
        var parts = iso.split('-');
        return new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
    }
    function startOfDay(d) { var c = new Date(d); c.setHours(0, 0, 0, 0); return c; }
    function addDays(d, n) { var c = new Date(d); c.setDate(c.getDate() + n); return c; }

    // ---------------------------------------------------------------------
    // Calendar popover (one instance per widget, reused across all fields)
    // ---------------------------------------------------------------------
    function ensurePopover(w) {
        if (w.popover) return w.popover;

        var pop = document.createElement('div');
        pop.className = 'ff-calendar-popover hidden';
        pop.innerHTML =
            '<div class="flex items-center justify-between mb-2">' +
                '<button type="button" class="ff-cal-prev text-gray-400 hover:text-blue-600 px-2"><i class="fas fa-chevron-left text-[10px]"></i></button>' +
                '<span class="ff-cal-title text-xs font-bold text-gray-800"></span>' +
                '<button type="button" class="ff-cal-next text-gray-400 hover:text-blue-600 px-2"><i class="fas fa-chevron-right text-[10px]"></i></button>' +
            '</div>' +
            '<div class="grid grid-cols-7 gap-0.5 mb-1">' +
                DAY_NAMES.map(function (d) { return '<div class="text-center text-[9px] font-semibold text-gray-400">' + d + '</div>'; }).join('') +
            '</div>' +
            '<div class="ff-cal-grid grid grid-cols-7 gap-0.5"></div>';
        document.body.appendChild(pop);

        pop.querySelector('.ff-cal-prev').addEventListener('click', function () {
            w.viewDate.setMonth(w.viewDate.getMonth() - 1);
            renderCalendar(w);
        });
        pop.querySelector('.ff-cal-next').addEventListener('click', function () {
            w.viewDate.setMonth(w.viewDate.getMonth() + 1);
            renderCalendar(w);
        });

        document.addEventListener('click', function (e) {
            if (!w.popover || w.popover.classList.contains('hidden')) return;
            if (w.popover.contains(e.target) || (w.activeField && w.activeField.trigger.contains(e.target))) return;
            closeCalendar(w);
        });

        w.popover = pop;
        return pop;
    }

    function renderCalendar(w) {
        var pop = w.popover;
        var field = w.activeField;
        if (!pop || !field) return;

        var year = w.viewDate.getFullYear();
        var month = w.viewDate.getMonth();
        pop.querySelector('.ff-cal-title').textContent = MONTH_NAMES[month] + ' ' + year;

        var grid = pop.querySelector('.ff-cal-grid');
        grid.innerHTML = '';

        var firstDay = new Date(year, month, 1).getDay();
        var daysInMonth = new Date(year, month + 1, 0).getDate();
        var today = startOfDay(new Date());
        var minDate = field.getMinDate ? field.getMinDate() : today;
        var selected = field.hidden.value ? parseISO(field.hidden.value) : null;

        for (var i = 0; i < firstDay; i++) {
            grid.appendChild(document.createElement('div'));
        }

        for (var day = 1; day <= daysInMonth; day++) {
            var d = new Date(year, month, day);
            var cell = document.createElement('div');
            cell.className = 'ff-cal-day';
            cell.textContent = day;

            var disabled = d < minDate;
            if (disabled) cell.classList.add('ff-cal-disabled');
            if (selected && d.getTime() === startOfDay(selected).getTime()) cell.classList.add('ff-cal-selected');
            if (d.getTime() === today.getTime()) cell.classList.add('ff-cal-today');

            if (!disabled) {
                cell.addEventListener('click', function (chosen) {
                    return function () { selectDate(w, chosen); };
                }(d));
            }
            grid.appendChild(cell);
        }
    }

    function selectDate(w, date) {
        var field = w.activeField;
        if (!field) return;
        field.hidden.value = toISO(date);
        field.display.textContent = toDisplay(date);
        field.display.classList.remove('text-gray-400');
        field.display.classList.add('text-gray-800', 'font-medium');
        if (field.onSelect) field.onSelect(date);
        closeCalendar(w);
    }

    function openCalendar(w, field) {
        var pop = ensurePopover(w);
        w.activeField = field;

        var current = field.hidden.value ? parseISO(field.hidden.value) : (field.getMinDate ? field.getMinDate() : new Date());
        w.viewDate = new Date(current.getFullYear(), current.getMonth(), 1);

        var rect = field.trigger.getBoundingClientRect();
        pop.style.top = (rect.bottom + window.scrollY + 4) + 'px';
        pop.style.left = (rect.left + window.scrollX) + 'px';
        pop.classList.remove('hidden');

        renderCalendar(w);
    }

    function closeCalendar(w) {
        if (w.popover) w.popover.classList.add('hidden');
        w.activeField = null;
    }

    /**
     * Wire a trigger button + hidden input as a date field.
     * options: { getMinDate: fn() => Date, onSelect: fn(date), defaultDaysFromNow: number }
     */
    function initDateField(w, trigger, hidden, display, options) {
        options = options || {};
        var field = {
            trigger: trigger,
            hidden: hidden,
            display: display,
            getMinDate: options.getMinDate,
            onSelect: options.onSelect
        };

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            if (w.activeField === field && w.popover && !w.popover.classList.contains('hidden')) {
                closeCalendar(w);
            } else {
                openCalendar(w, field);
            }
        });

        if (typeof options.defaultDaysFromNow === 'number' && !hidden.value) {
            var def = addDays(startOfDay(new Date()), options.defaultDaysFromNow);
            hidden.value = toISO(def);
            display.textContent = toDisplay(def);
            display.classList.remove('text-gray-400');
            display.classList.add('text-gray-800', 'font-medium');
        }

        return field;
    }

    // ---------------------------------------------------------------------
    // Airport autocomplete (works for the standard row and multi-city legs)
    // ---------------------------------------------------------------------
    var airportCache = {};

    function wireAirportAutocomplete(input) {
        if (input.dataset.ffWired) return;
        input.dataset.ffWired = '1';

        var wrap = input.closest('.relative');
        var sug = wrap.querySelector('.airport-suggestions') || wrap.parentElement.querySelector('.airport-suggestions');
        var hidden = wrap.parentElement.querySelector('input[type="hidden"]') || wrap.querySelector('input[type="hidden"]');
        var to;

        input.addEventListener('input', function () {
            clearTimeout(to);
            var q = this.value.trim();
            if (q.length < 2) { sug.classList.add('hidden'); return; }
            to = setTimeout(function () {
                if (airportCache[q]) { renderSuggestions(airportCache[q], sug, input, hidden); return; }
                fetch('/api/airports/search?q=' + encodeURIComponent(q))
                    .then(function (r) { return r.json(); })
                    .then(function (d) { airportCache[q] = d; renderSuggestions(d, sug, input, hidden); })
                    .catch(function () { sug.classList.add('hidden'); });
            }, 200);
        });

        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !sug.contains(e.target)) sug.classList.add('hidden');
        });
    }

    function renderSuggestions(data, container, input, hidden) {
        if (!data || !data.length) { container.classList.add('hidden'); return; }
        container.innerHTML = '';
        data.slice(0, 6).forEach(function (item) {
            var d = document.createElement('div');
            d.className = 'px-3 py-2 hover:bg-blue-50 cursor-pointer flex justify-between items-center border-b border-gray-50 text-xs';
            var name = item.name || item.city || '';
            var city = item.city || '';
            var country = item.country || '';
            var code = item.iata_code || item.iataCode || item.code || '';
            d.innerHTML = '<div><span class="font-medium text-gray-900">' + name + '</span><span class="text-gray-400 ml-1">' + city + (country ? ', ' + country : '') + '</span></div><span class="font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded text-[10px]">' + code + '</span>';
            d.addEventListener('click', function () {
                input.value = name + ' (' + code + ')';
                hidden.value = code;
                container.classList.add('hidden');
            });
            container.appendChild(d);
        });
        container.classList.remove('hidden');
    }

    function wireAllAutocompleteIn(root) {
        root.querySelectorAll('.airport-autocomplete').forEach(wireAirportAutocomplete);
    }

    // ---------------------------------------------------------------------
    // Multi-city leg rows
    // ---------------------------------------------------------------------
    function legRowHTML(idPrefix, legNumber) {
        return '' +
            '<div class="ff-leg-row grid grid-cols-2 md:grid-cols-9 gap-3 items-end" data-leg="' + legNumber + '">' +
                '<div class="relative md:col-span-3">' +
                    '<label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">From</label>' +
                    '<div class="relative">' +
                        '<i class="fas fa-plane-departure absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>' +
                        '<input type="text" class="w-full pl-7 pr-2 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 airport-autocomplete" placeholder="City or Airport" autocomplete="off" required>' +
                        '<input type="hidden" name="from_iata[]">' +
                    '</div>' +
                    '<div class="airport-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-48 overflow-y-auto"></div>' +
                '</div>' +
                '<div class="relative md:col-span-3">' +
                    '<label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">To</label>' +
                    '<div class="relative">' +
                        '<i class="fas fa-plane-arrival absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>' +
                        '<input type="text" class="w-full pl-7 pr-2 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 airport-autocomplete" placeholder="City or Airport" autocomplete="off" required>' +
                        '<input type="hidden" name="to_iata[]">' +
                    '</div>' +
                    '<div class="airport-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-48 overflow-y-auto"></div>' +
                '</div>' +
                '<div class="relative md:col-span-2">' +
                    '<label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Date</label>' +
                    '<button type="button" class="ff-leg-date-trigger ff-date-trigger py-2 px-2.5 border border-gray-200 rounded-lg text-xs text-gray-800 flex items-center gap-1.5 w-full">' +
                        '<i class="fas fa-calendar-alt text-gray-300 text-[10px]"></i>' +
                        '<span class="ff-date-display text-gray-400">Select date</span>' +
                    '</button>' +
                    '<input type="hidden" name="date[]">' +
                '</div>' +
                '<div class="md:col-span-1 flex justify-end">' +
                    '<button type="button" class="ff-remove-leg text-gray-300 hover:text-red-500 p-2" title="Remove flight"><i class="fas fa-times-circle"></i></button>' +
                '</div>' +
            '</div>';
    }

    function addLeg(w) {
        var rows = w.multiCityRows;
        if (rows.children.length >= MAX_LEGS) return;

        w.legCounter++;
        var wrapper = document.createElement('div');
        wrapper.innerHTML = legRowHTML(w.idPrefix, w.legCounter);
        var row = wrapper.firstElementChild;
        rows.appendChild(row);

        wireAllAutocompleteIn(row);

        var trigger = row.querySelector('.ff-leg-date-trigger');
        var hidden = row.querySelector('input[name="date[]"]');
        var display = trigger.querySelector('.ff-date-display');
        var legIndex = rows.children.length - 1;

        initDateField(w, trigger, hidden, display, {
            getMinDate: function () {
                // Each leg must be on/after the previous leg's chosen date (or tomorrow for the first leg).
                var prevRow = row.previousElementSibling;
                if (prevRow) {
                    var prevHidden = prevRow.querySelector('input[name="date[]"]');
                    if (prevHidden && prevHidden.value) return parseISO(prevHidden.value);
                }
                return addDays(startOfDay(new Date()), 1);
            },
            defaultDaysFromNow: legIndex === 0 ? 1 : null
        });

        row.querySelector('.ff-remove-leg').addEventListener('click', function () {
            if (rows.children.length <= MIN_LEGS) return;
            row.remove();
            updateAddLegVisibility(w);
        });

        updateAddLegVisibility(w);
    }

    function updateAddLegVisibility(w) {
        w.addLegBtn.classList.toggle('hidden', w.multiCityRows.children.length >= MAX_LEGS);
    }

    // ---------------------------------------------------------------------
    // Trip type switching
    // ---------------------------------------------------------------------
    function setTripType(w, type) {
        w.tripTypeInput.value = type;

        w.root.querySelectorAll('.ff-trip-tab').forEach(function (t) {
            t.classList.toggle('active', t.getAttribute('data-type') === type);
        });

        if (type === 'multi_city') {
            w.standardRow.classList.add('hidden');
            w.multiCityRows.classList.remove('hidden');
            w.addLegBtn.classList.remove('hidden');
            w.fromInput.removeAttribute('required');
            w.toInput.removeAttribute('required');

            if (w.multiCityRows.children.length === 0) {
                addLeg(w);
                addLeg(w);
            }
            updateAddLegVisibility(w);
        } else {
            w.standardRow.classList.remove('hidden');
            w.multiCityRows.classList.add('hidden');
            w.addLegBtn.classList.add('hidden');
            w.fromInput.setAttribute('required', 'required');
            w.toInput.setAttribute('required', 'required');

            var returnWrap = w.returnWrap;
            if (type === 'one_way') {
                returnWrap.style.opacity = '0.4';
                returnWrap.style.pointerEvents = 'none';
                w.returnHidden.removeAttribute('required');
            } else {
                returnWrap.style.opacity = '1';
                returnWrap.style.pointerEvents = 'auto';
            }
        }
    }

    // ---------------------------------------------------------------------
    // Form submission (AJAX, shared overlay)
    // ---------------------------------------------------------------------
    function submitSearch(w, e) {
        e.preventDefault();

        var tripType = w.tripTypeInput.value;
        if (tripType !== 'multi_city') {
            if (!w.fromIataInput.value || !w.toIataInput.value) {
                alert('Please select valid airports from the suggestions.');
                return false;
            }
            if (!w.departHidden.value) {
                alert('Please choose a departure date.');
                return false;
            }
            if (tripType === 'round_trip' && !w.returnHidden.value) {
                alert('Please choose a return date.');
                return false;
            }
        } else {
            var legs = w.multiCityRows.querySelectorAll('.ff-leg-row');
            for (var i = 0; i < legs.length; i++) {
                var f = legs[i].querySelector('input[name="from_iata[]"]');
                var t = legs[i].querySelector('input[name="to_iata[]"]');
                var d = legs[i].querySelector('input[name="date[]"]');
                if (!f.value || !t.value || !d.value) {
                    alert('Please complete every flight leg (airports and date).');
                    return false;
                }
            }
        }

        var fd = new FormData(w.form);
        var overlay = w.overlay;
        var statusText = w.statusText;

        overlay.classList.remove('hidden');
        overlay.classList.add('flex');

        var messages = ['Comparing prices across 500+ airlines', 'Checking direct and connecting flights', 'Finding the best fares for your dates', 'Almost there, finalizing results...'];
        var mi = 0;
        w.statusInterval = setInterval(function () {
            mi++;
            if (mi < messages.length) statusText.textContent = messages[mi];
        }, 3000);

        w.abortController = new AbortController();

        fetch('/api/search', {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            signal: w.abortController.signal
        }).then(function (r) { return r.json(); }).then(function (data) {
            clearInterval(w.statusInterval);
            window.location.href = data.redirect || '/flights/results';
        }).catch(function (err) {
            clearInterval(w.statusInterval);
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
            if (err.name === 'AbortError') return;
            window.location.href = '/flights/results';
        });

        return false;
    }

    function cancelSearch(idPrefix) {
        var w = widgets[idPrefix];
        if (!w) return;
        if (w.abortController) w.abortController.abort();
        clearInterval(w.statusInterval);
        w.overlay.classList.add('hidden');
        w.overlay.classList.remove('flex');
    }
    window.ffCancelSearch = cancelSearch;

    // ---------------------------------------------------------------------
    // Entry point
    // ---------------------------------------------------------------------
    function init(idPrefix) {
        var root = document.querySelector('.ff-search-widget[data-prefix="' + idPrefix + '"]');
        var form = document.getElementById(idPrefix + 'FlightForm');
        if (!root || !form) return;

        var w = {
            idPrefix: idPrefix,
            root: root,
            form: form,
            tripTypeInput: document.getElementById(idPrefix + '_trip_type'),
            standardRow: document.getElementById(idPrefix + '_standardRow'),
            multiCityRows: document.getElementById(idPrefix + '_multiCityRows'),
            addLegBtn: document.getElementById(idPrefix + '_addLeg'),
            fromInput: document.getElementById(idPrefix + '_from'),
            toInput: document.getElementById(idPrefix + '_to'),
            fromIataInput: document.getElementById(idPrefix + '_from_iata'),
            toIataInput: document.getElementById(idPrefix + '_to_iata'),
            returnWrap: document.getElementById(idPrefix + '_returnWrap'),
            departHidden: document.getElementById(idPrefix + '_depart'),
            returnHidden: document.getElementById(idPrefix + '_return'),
            overlay: document.getElementById(idPrefix + 'SearchOverlay'),
            statusText: document.getElementById(idPrefix + 'SearchStatusText'),
            legCounter: 0,
            popover: null,
            activeField: null,
            viewDate: new Date()
        };
        widgets[idPrefix] = w;

        wireAllAutocompleteIn(root);

        initDateField(w, document.getElementById(idPrefix + '_depart_trigger'), w.departHidden, document.querySelector('#' + idPrefix + '_depart_trigger .ff-date-display'), {
            getMinDate: function () { return addDays(startOfDay(new Date()), 1); },
            defaultDaysFromNow: 1,
            onSelect: function () { renderCalendar(w); }
        });

        initDateField(w, document.getElementById(idPrefix + '_return_trigger'), w.returnHidden, document.querySelector('#' + idPrefix + '_return_trigger .ff-date-display'), {
            getMinDate: function () {
                return w.departHidden.value ? parseISO(w.departHidden.value) : addDays(startOfDay(new Date()), 1);
            },
            defaultDaysFromNow: 8
        });

        root.querySelectorAll('.ff-trip-tab').forEach(function (tab) {
            tab.addEventListener('click', function () {
                setTripType(w, tab.getAttribute('data-type'));
            });
        });

        w.addLegBtn.addEventListener('click', function () { addLeg(w); });

        form.addEventListener('submit', function (e) { submitSearch(w, e); });

        // Pre-fill destination from URL params (matches prior behaviour on the homepage).
        var params = new URLSearchParams(window.location.search);
        if (params.get('to') && w.toInput) {
            w.toInput.value = params.get('to');
            w.toIataInput.value = params.get('to');
        }
    }

    window.initFlightSearchWidget = init;
})();
