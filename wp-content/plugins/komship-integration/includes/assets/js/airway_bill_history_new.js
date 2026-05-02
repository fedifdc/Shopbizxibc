jQuery(document).ready(function($) {

    const $historyDetailModal = $('#airway-bill-tracking-modal');
    const $historyTimeline = $('.history-timeline');
    const loadingSpinner = $('.loading-spinner');

    $('#view-history-btn').on('click', function() {
        const { courier, service, tracking, nonce, ajax_url } = trackingData;
        loadingSpinner.show();
        $.ajax({
            url: ajax_url,
            type: 'GET',
            data: {
                nonce: nonce,
                airway_bill: tracking,
                shipping: courier,
                action: 'komship_history_airway_bill'
            },
            success: function(response) {
                loadingSpinner.hide();
                renderHistoryTimeline(response.data.history);
            },
            error: function(xhr, status, error) {
                console.error('Ajax error:', error);
            }
        });

        $historyDetailModal.fadeIn();
    });

    $('.close-btn').on('click', function() {
        $historyDetailModal.fadeOut();
    });

    $historyDetailModal.on('click', function(event) {
        if (event.target === this) {
            $(this).fadeOut();
        }
    });

    function renderHistoryTimeline(history) {
        const timelineHTML = history.map((item, index) => {
            const icon = getStatusIcon(item.status);
            const timelineClass = index === history.length - 1
                ? 'timeline-circle'
                : 'timeline-circle connected';
            console.log(JSON.stringify(item));
            console.log(item);
            return `
                <div class="history-item">
                    <div class="${timelineClass}"></div>
                    <div class="history-content">
                        <div>
                            <div class="desc">${escapeHTML(item.desc)}</div>
                            <div class="date">${item.date}</div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
       
        $historyTimeline.html(timelineHTML);
    }

    function getStatusIcon(status) {
        const icons = {
            "Problem": '<i class="fas fa-exclamation-triangle"></i>',
            "Pickup": '<i class="fas fa-box"></i>',
            "Process": '<i class="fas fa-sync-alt"></i>',
            "Delivered": '<i class="fas fa-check-circle"></i>'
        };
        return icons[status] || '<i class="fas fa-circle"></i>';
    }

    function escapeHTML(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
});