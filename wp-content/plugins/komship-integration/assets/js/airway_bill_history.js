   console.log("ITS FROM ASSET");
 jQuery(document).ready(function($) {
     console.log("ITS FROM ASSET");
        const data = {
            "meta": {
                "message": "success get data",
                "code": 200,
                "status": "success"
            },
            "data": {
                "airway_bill": "JO9000188085",
                "last_status": "Delivered",
                "history": [
                    {
                        "desc": "Pengirim Sedang Mempersiapkan Paket",
                        "date": "2022-11-29 20:08:11",
                        "code": "Pickup Failure",
                        "status": "Problem"
                    },
                    {
                        "desc": "Paket Udah Dipickup Kurir [Saiful Karim][TH MANYAR]",
                        "date": "2022-11-30 09:42:47",
                        "code": "Pick up scan",
                        "status": "Pickup"
                    },
                    {
                        "desc": "Berangkat ke TH TANDES",
                        "date": "2022-11-30 22:54:17",
                        "code": "Sending scan",
                        "status": "Process"
                    },
                    {
                        "desc": "Nyampe di [][MH SURABAYA]",
                        "date": "2022-11-30 23:39:53",
                        "code": "Arrival scan",
                        "status": "Process"
                    },
                    {
                        "desc": "Berangkat ke MH SURABAYA",
                        "date": "2022-12-01 03:34:29",
                        "code": "Sending scan",
                        "status": "Process"
                    },
                    {
                        "desc": "Nyampe di [][TH SIMOKERTO]",
                        "date": "2022-12-01 04:24:29",
                        "code": "Arrival scan",
                        "status": "Process"
                    },
                    {
                        "desc": "Lagi Dicoba Dianter Kurir [Hasanuddin][TH SIMOKERTO]",
                        "date": "2022-12-01 07:46:01",
                        "code": "Delivery scan",
                        "status": "Process"
                    },
                    {
                        "desc": "Paket Udah Diterima Oleh Siti Holida, Fina",
                        "date": "2022-12-01 11:58:08",
                        "code": "POD scan",
                        "status": "Delivered"
                    }
                ]
            }
        };

        const $historyDetailModal = $('#history-detail-modal-tracking');
        const $historyTimeline = $('.history-timeline');

        $('#view-history-btn').on('click', function() {
            renderHistoryTimeline(data.data.history);
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
                const timelineClass = index === 0 
                    ? 'timeline-circle' 
                    : 'timeline-circle connected';
                console.log(JSON.stringify(item));
                return `
                    <div class="history-item">
                        <div class="${timelineClass}"></div>
                        <div class="history-content">
                            <div class="status-icon">${icon}</div>
                            <div class="desc">${escapeHTML(item.desc)}</div>
                            <div class="date">${item.date}</div>
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

        function formatDate(dateString) {
            // Split the date string into components
            const [datePart, timePart] = dateString.split(' ');
            const [day, month, year] = datePart.split('-');
        
            // Create a valid JavaScript Date object
            const date = new Date(year, month - 1, day, ...timePart.split(':'));
        
            // Format the date using toLocaleString
            return date.toLocaleString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            });
        }

        function escapeHTML(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }
    });