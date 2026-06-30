<style>
/* wrapper */
.view-filter{
    display: flex;
    gap: 8px;
}

/* grid & list common */
.view-filter span{
    width: 36px;
    height: 32px;
    border: 1px solid #ddd;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .2s ease;
}

/* active state */
.view-filter span.active{
    background: #1b5e20;
    border-color: #1b5e20;
}

/* -------- list icon (3 lines) -------- */
.list-icon{
    width: 14px;
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.list-icon span{
    height: 2px;
    width: 100%;
    background: #ffff;
    border-radius: 2px;
}

/* active color change */
.view-filter span.active .list-icon span{
    background: #fff;
}

/* hover */
.view-filter span:hover{
    background: #0000;
    color:red;
}
.list-icon span:hover{
    background: #0000;
    color:red;
    
}

</style>

<div class="card col-md-12">
    <div style="padding: 10px 0;">
        <div class="filter">
            <div class="view-filter">

               <span id="filter-open" style="display: none;" class="filter-open"><i class="fas fa-filter"></i></span>
                <span class="grid" id="grid">
                    <i class="fas fa-th"></i>
                </span>

                {{-- Custom List Icon --}}
                <span class="list" id="list">
                    <span class="list-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </span>

            </div>
        </div>
    </div>
</div>