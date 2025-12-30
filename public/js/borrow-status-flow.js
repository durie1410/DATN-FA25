/**
 * Borrow Status Flow Manager
 * Quản lý và validate các bước chuyển trạng thái đơn mượn sách
 */

class BorrowStatusFlow {
    constructor() {
        // Định nghĩa flow các bước
        this.statusFlow = {
            'don_hang_moi': {
                step: 1,
                next: ['dang_chuan_bi_sach'],
                label: 'Đơn hàng Mới'
            },
            'dang_chuan_bi_sach': {
                step: 2,
                next: ['cho_ban_giao_van_chuyen'],
                label: 'Đang Chuẩn bị Sách'
            },
            'cho_ban_giao_van_chuyen': {
                step: 3,
                next: ['dang_giao_hang'],
                label: 'Chờ Bàn giao Vận chuyển'
            },
            'dang_giao_hang': {
                step: 4,
                next: ['giao_hang_thanh_cong', 'giao_hang_that_bai'],
                label: 'Đang Giao hàng'
            },
            'giao_hang_thanh_cong': {
                step: 5,
                next: ['da_muon_dang_luu_hanh'],
                label: 'Giao hàng Thành công'
            },
            'giao_hang_that_bai': {
                step: 6,
                next: ['dang_van_chuyen_tra_ve', 'da_nhan_va_kiem_tra'],
                label: 'Giao hàng Thất bại'
            },
            'da_muon_dang_luu_hanh': {
                step: 7,
                next: ['cho_tra_sach'],
                label: 'Đã Mượn (Đang Lưu hành)'
            },
            'cho_tra_sach': {
                step: 8,
                next: ['dang_van_chuyen_tra_ve'],
                label: 'Chờ Trả sách'
            },
            'dang_van_chuyen_tra_ve': {
                step: 9,
                next: ['da_nhan_va_kiem_tra'],
                label: 'Đang Vận chuyển Trả về'
            },
            'da_nhan_va_kiem_tra': {
                step: 10,
                next: ['hoan_tat_don_hang'],
                label: 'Đã Nhận & Kiểm tra'
            },
            'hoan_tat_don_hang': {
                step: 11,
                next: [],
                label: 'Hoàn tất Đơn hàng'
            }
        };

        // Mapping các action với status
        this.actionToStatus = {
            'confirm-delivery': 'da_muon_dang_luu_hanh',
            'request-return': 'cho_tra_sach',
            'return-book': 'dang_van_chuyen_tra_ve'
        };

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.validateAllActions();
    }

    /**
     * Kiểm tra xem có thể chuyển từ status hiện tại sang status mới không
     */
    canTransition(currentStatus, targetStatus) {
        const current = this.statusFlow[currentStatus];
        if (!current) {
            console.warn(`Status không tồn tại: ${currentStatus}`);
            return false;
        }

        return current.next.includes(targetStatus);
    }

    /**
     * Validate action dựa trên trạng thái hiện tại
     */
    validateAction(borrowId, currentStatus, action) {
        const targetStatus = this.actionToStatus[action];
        
        if (!targetStatus) {
            console.warn(`Action không được định nghĩa: ${action}`);
            return {
                valid: false,
                message: 'Hành động không hợp lệ'
            };
        }

        if (!this.canTransition(currentStatus, targetStatus)) {
            const current = this.statusFlow[currentStatus];
            const target = this.statusFlow[targetStatus];
            
            return {
                valid: false,
                message: `Không thể thực hiện hành động này. Trạng thái hiện tại: "${current?.label || currentStatus}". Bạn cần hoàn thành các bước trước đó.`
            };
        }

        return {
            valid: true,
            message: ''
        };
    }

    /**
     * Setup event listeners cho các form action
     */
    setupEventListeners() {
        // Intercept form submissions
        document.addEventListener('submit', (e) => {
            const form = e.target;
            
            // Kiểm tra nếu là form liên quan đến borrow status
            if (form.id === 'requestReturnForm' || form.id === 'returnBookForm' || form.id === 'confirmDeliveryForm') {
                const action = this.getActionFromForm(form);
                const borrowId = this.getBorrowIdFromForm(form);
                const currentStatus = this.getCurrentStatus(borrowId);

                if (action && currentStatus) {
                    const validation = this.validateAction(borrowId, currentStatus, action);
                    
                    if (!validation.valid) {
                        e.preventDefault();
                        this.showError(validation.message);
                        return false;
                    }
                }
            }
        });

        // Intercept button clicks
        document.addEventListener('click', (e) => {
            const button = e.target.closest('[data-borrow-action]');
            if (button) {
                const action = button.getAttribute('data-borrow-action');
                const borrowId = button.getAttribute('data-borrow-id');
                const currentStatus = button.getAttribute('data-current-status');

                if (action && currentStatus) {
                    const validation = this.validateAction(borrowId, currentStatus, action);
                    
                    if (!validation.valid) {
                        e.preventDefault();
                        e.stopPropagation();
                        this.showError(validation.message);
                        return false;
                    }
                }
            }
        });
    }

    /**
     * Validate tất cả các action buttons trên trang
     */
    validateAllActions() {
        // Validate confirm delivery buttons
        document.querySelectorAll('[data-borrow-action="confirm-delivery"]').forEach(button => {
            const currentStatus = button.getAttribute('data-current-status');
            const validation = this.validateAction(
                button.getAttribute('data-borrow-id'),
                currentStatus,
                'confirm-delivery'
            );
            
            if (!validation.valid) {
                button.disabled = true;
                button.title = validation.message;
                button.style.opacity = '0.5';
                button.style.cursor = 'not-allowed';
            }
        });

        // Validate request return buttons
        document.querySelectorAll('[data-borrow-action="request-return"]').forEach(button => {
            const currentStatus = button.getAttribute('data-current-status');
            const validation = this.validateAction(
                button.getAttribute('data-borrow-id'),
                currentStatus,
                'request-return'
            );
            
            if (!validation.valid) {
                button.disabled = true;
                button.title = validation.message;
                button.style.opacity = '0.5';
                button.style.cursor = 'not-allowed';
            }
        });

        // Validate return book buttons
        document.querySelectorAll('[data-borrow-action="return-book"]').forEach(button => {
            const currentStatus = button.getAttribute('data-current-status');
            const validation = this.validateAction(
                button.getAttribute('data-borrow-id'),
                currentStatus,
                'return-book'
            );
            
            if (!validation.valid) {
                button.disabled = true;
                button.title = validation.message;
                button.style.opacity = '0.5';
                button.style.cursor = 'not-allowed';
            }
        });
    }

    /**
     * Lấy action từ form
     */
    getActionFromForm(form) {
        const action = form.action;
        if (action.includes('confirm-delivery')) return 'confirm-delivery';
        if (action.includes('request-return')) return 'request-return';
        if (action.includes('return-book')) return 'return-book';
        return null;
    }

    /**
     * Lấy borrow ID từ form
     */
    getBorrowIdFromForm(form) {
        const match = form.action.match(/\/borrows\/(\d+)\//);
        return match ? match[1] : null;
    }

    /**
     * Lấy trạng thái hiện tại từ data
     */
    getCurrentStatus(borrowId) {
        if (typeof borrowsData !== 'undefined' && borrowsData[borrowId]) {
            return borrowsData[borrowId].trang_thai_chi_tiet;
        }
        
        // Fallback: tìm trong DOM
        const statusElement = document.querySelector(`[data-borrow-id="${borrowId}"]`);
        if (statusElement) {
            return statusElement.getAttribute('data-current-status');
        }
        
        return null;
    }

    /**
     * Hiển thị lỗi
     */
    showError(message) {
        // Tạo toast notification
        const toast = document.createElement('div');
        toast.className = 'status-flow-toast';
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #dc3545;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            max-width: 400px;
            animation: slideInRight 0.3s ease;
        `;
        toast.innerHTML = `
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 20px;">⚠️</span>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    /**
     * Hiển thị progress indicator
     */
    showProgressIndicator(borrowId, currentStatus) {
        const current = this.statusFlow[currentStatus];
        if (!current) return;

        const progress = (current.step / 11) * 100;
        
        // Tạo progress bar nếu chưa có
        let progressBar = document.getElementById(`progress-${borrowId}`);
        if (!progressBar) {
            progressBar = document.createElement('div');
            progressBar.id = `progress-${borrowId}`;
            progressBar.className = 'status-progress-bar';
            progressBar.style.cssText = `
                width: 100%;
                height: 6px;
                background-color: #e0e0e0;
                border-radius: 3px;
                margin: 10px 0;
                overflow: hidden;
            `;
            
            const progressFill = document.createElement('div');
            progressFill.className = 'status-progress-fill';
            progressFill.style.cssText = `
                height: 100%;
                background: linear-gradient(90deg, #28a745, #20c997);
                transition: width 0.3s ease;
                width: ${progress}%;
            `;
            
            progressBar.appendChild(progressFill);
            
            // Tìm container để chèn progress bar
            const container = document.querySelector(`[data-borrow-id="${borrowId}"]`)?.closest('.book-card');
            if (container) {
                container.insertBefore(progressBar, container.querySelector('.book-info'));
            }
        } else {
            const fill = progressBar.querySelector('.status-progress-fill');
            if (fill) {
                fill.style.width = `${progress}%`;
            }
        }
    }

    /**
     * Lấy thông tin bước tiếp theo
     */
    getNextStep(currentStatus) {
        const current = this.statusFlow[currentStatus];
        if (!current || current.next.length === 0) {
            return null;
        }

        const nextStatus = current.next[0]; // Lấy bước tiếp theo đầu tiên
        return {
            status: nextStatus,
            ...this.statusFlow[nextStatus]
        };
    }

    /**
     * Lấy danh sách tất cả các bước
     */
    getAllSteps() {
        return Object.keys(this.statusFlow)
            .map(key => ({
                key,
                ...this.statusFlow[key]
            }))
            .sort((a, b) => a.step - b.step);
    }
}

// Khởi tạo khi DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.borrowStatusFlow = new BorrowStatusFlow();
    });
} else {
    window.borrowStatusFlow = new BorrowStatusFlow();
}

// Thêm CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .status-progress-bar {
        position: relative;
    }
    
    .status-progress-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
`;
document.head.appendChild(style);
