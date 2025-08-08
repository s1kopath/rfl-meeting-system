import Toastify from 'toastify-js'

class ToastService {
    show(message, type = 'info', duration = 5000) {
        const config = {
            text: message,
            duration: duration,
            gravity: 'top',
            position: 'right',
            className: `toastify ${type}`,
            stopOnFocus: true,
            close: true,
            offset: {
                x: 20,
                y: 20
            },
            style: {
                background: this.getBackgroundColor(type),
                color: this.getTextColor(type),
                borderLeft: `4px solid ${this.getBorderColor(type)}`,
                borderRadius: '8px',
                boxShadow: '0 4px 12px rgba(0, 0, 0, 0.15)',
                zIndex: 999999,
                position: 'fixed',
                top: '20px',
                right: '20px',
                left: 'auto',
                maxWidth: '400px',
                minWidth: '300px',
                wordWrap: 'break-word',
                fontSize: '14px',
                fontWeight: '500',
                padding: '12px 16px',
                margin: '0',
                transform: 'none',
                display: 'block',
                float: 'none',
                transition: 'all 0.3s ease-in-out',
                animation: 'slideInRight 0.3s ease-out'
            }
        }

        return Toastify(config).showToast()
    }

    getBackgroundColor(type) {
        const colors = {
            success: '#f0fdf4',
            error: '#fef2f2',
            warning: '#fffbeb',
            info: '#eff6ff'
        }
        return colors[type] || colors.info
    }

    getTextColor(type) {
        const colors = {
            success: '#166534',
            error: '#dc2626',
            warning: '#d97706',
            info: '#1d4ed8'
        }
        return colors[type] || colors.info
    }

    getBorderColor(type) {
        const colors = {
            success: '#22c55e',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#3b82f6'
        }
        return colors[type] || colors.info
    }

    success(message, duration = 5000) {
        return this.show(message, 'success', duration)
    }

    error(message, duration = 7000) {
        return this.show(message, 'error', duration)
    }

    warning(message, duration = 5000) {
        return this.show(message, 'warning', duration)
    }

    info(message, duration = 5000) {
        return this.show(message, 'info', duration)
    }

    // Show validation errors
    showValidationErrors(errors) {
        if (typeof errors === 'object' && errors !== null) {
            Object.values(errors).forEach(error => {
                if (Array.isArray(error)) {
                    error.forEach(err => this.error(err))
                } else {
                    this.error(error)
                }
            })
        }
    }

    // Show session messages
    showSessionMessage(message, type = 'success') {
        if (message) {
            this[type](message)
        }
    }
}

export default new ToastService() 