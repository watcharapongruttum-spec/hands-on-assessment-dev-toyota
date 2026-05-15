let refreshTimer = null;
let firstEventTime = null;

window.handleCarModelChanged = () => {

    const now = Date.now();

    // event แรก
    if (!firstEventTime) {
        firstEventTime = now;
    }

    // reset timer เดิม
    clearTimeout(refreshTimer);

    const elapsed = now - firstEventTime;

    // ถ้าเกิน 3 วิแล้ว refresh เลย
    if (elapsed >= 3000) {

        doRefresh();

        return;
    }

    // รอ event เงียบ 2 วิ
    refreshTimer = setTimeout(() => {

        doRefresh();

    }, 2000);
};

function doRefresh() {

    firstEventTime = null;

    if (window.Livewire) {

        Livewire.dispatch('refresh-car-model-table');
    }
}