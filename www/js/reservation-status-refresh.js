/**
 * Auto-refresh reservation status without page reload
 * Updates status badges and counts every 10 seconds
 */

(function () {
    const REFRESH_INTERVAL = 10000; // 10 seconds
    let refreshTimer = null;

    function updateSingleReservationStatus() {
        const urlParams = new URLSearchParams(window.location.search);
        const reservationId = urlParams.get('reservation_id');

        if (!reservationId) return;

        fetch(`api/reservation-status.php?reservation_id=${reservationId}`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch status');
                return response.json();
            })
            .then(data => {
                const statusBadges = document.querySelectorAll('[data-reservation-status]');
                statusBadges.forEach(badge => {
                    badge.className = `badge ${data.badgeClass}`;
                    badge.textContent = data.status;
                });

                const cancelBtn = document.querySelector('[data-cancel-btn]');
                if (cancelBtn) {
                    if (data.canCancel) {
                        cancelBtn.style.display = '';
                    } else {
                        cancelBtn.style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error refreshing reservation status:', error));
    }

    function updateDashboardStatus() {
        const showAll = new URLSearchParams(window.location.search).get('show_all');
        const url = showAll ? `api/reservation-status.php?show_all=${showAll}` : 'api/reservation-status.php';

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch status');
                return response.json();
            })
            .then(data => {
                const activeCountEl = document.querySelector('[data-active-count]');
                if (activeCountEl) {
                    activeCountEl.textContent = data.active_count;
                }

                const completedCountEl = document.querySelector('[data-completed-count]');
                if (completedCountEl) {
                    completedCountEl.textContent = data.completed_count;
                }

                data.reservations.forEach(reservation => {
                    const reservationEl = document.getElementById(`reservation-${reservation.id}`);
                    if (!reservationEl) return;

                    const badge = reservationEl.querySelector('[data-status-badge]');
                    if (badge) {
                        badge.className = `badge ${reservation.badgeClass}`;
                        badge.textContent = reservation.status;
                    }

                    const cancelBtn = reservationEl.querySelector('[data-cancel-btn]');
                    if (cancelBtn) {
                        if (reservation.canCancel) {
                            cancelBtn.style.display = '';
                        } else {
                            cancelBtn.style.display = 'none';
                        }
                    }
                });
            })
            .catch(error => console.error('Error refreshing dashboard status:', error));
    }

    function startAutoRefresh() {
        const isSingleOrder = window.location.pathname.includes('single-order.php');
        const isDashboard = window.location.pathname.includes('user-dashboard.php');

        if (isSingleOrder) {
            updateSingleReservationStatus();
            refreshTimer = setInterval(updateSingleReservationStatus, REFRESH_INTERVAL);
        } else if (isDashboard) {
            updateDashboardStatus();
            refreshTimer = setInterval(updateDashboardStatus, REFRESH_INTERVAL);
        }
    }

    function stopAutoRefresh() {
        if (refreshTimer) {
            clearInterval(refreshTimer);
            refreshTimer = null;
        }
    }

    document.addEventListener('DOMContentLoaded', startAutoRefresh);

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
        }
    });
})();
