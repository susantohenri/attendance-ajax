jQuery(() => {
    function attendanceAjaxReload() {
        jQuery.get(`${attendace_ajax_attributes.ajax_url}?id=${attendace_ajax_attributes.id}`, entries => {
            var thead = `<thead><tr><th>${['Name', 'Attendance', 'Guest', 'Greeting'].join('</th><th>')}</th></tr></thead>`;
            var tbody = entries.map(entry => {
                return `
                    <td>${entry[1].value}</td>
                    <td>${entry[2].value}</td>
                    <td>${entry[3].value}</td>
                    <td>${entry[4].value}</td>
                `
            }).join('</tr><tr>')
            tbody = `<tbody><tr>${tbody}</tr></tbody>`
            var table = `<table id="attendance_entries">${thead}${tbody}</table>`
            jQuery('[id="attendance-ajax"]').html(table)
        })
    }
    attendanceAjaxReload()
    var intervalId = window.setInterval(attendanceAjaxReload, 5000);
    jQuery(`[id^="wpforms-form-${attendace_ajax_attributes.id}"]`).on('wpformsAjaxSubmitSuccess', attendanceAjaxReload)
})