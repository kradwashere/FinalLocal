<template>
    <b-modal v-model="showModal" title="Lacking Grades" :hide-footer="(viewScholar) ? false : true " style="--vz-modal-width: 700px;" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered>    
        <template v-if="viewScholar == false">
            <input class="form-control mb-3" v-model="keyword" v-if="scholars != 0" type="text" placeholder="Search Scholar">
            <hr class="text-muted"/>
            <div class="table-responsive">
                <table class="table table-bordered table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr class="fs-11">
                            <th width="7%" class="text-center">#</th>
                            <th width="73%">Name</th>
                            <th width="15%" class="text-center">Awarded Year</th>
                            <th width="5%" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody v-if="keyword != ''">
                        <tr v-for="(list,index) in filteredArray" v-bind:key="list.id" class="fs-11">
                            <td class="text-center">{{countPage + index}}</td>
                            <td>
                                <h5 class="fs-12 mb-0 text-dark">{{ list.firstname }} {{ list.lastname }}</h5>
                                <p class="text-muted fs-11 mb-n1">{{ list.spas_id }}</p>
                            </td>
                            <td class="text-center">{{list.awarded_year}}</td>
                            <td class="text-center">
                                <b-button @click="view(list)" variant="soft-primary" v-b-tooltip.hover title="Create Scholar Account" size="sm" class="edit-list"><i class="ri-user-add-fill align-bottom"></i> </b-button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr v-for="(list,index) in paginatedData" v-bind:key="list.id" class="fs-11">
                            <td class="text-center">{{countPage + index}}</td>
                            <td>
                                <h5 class="fs-12 mb-0 text-dark">{{ list.firstname }} {{ list.lastname }}</h5>
                                <p class="text-muted fs-11 mb-n1">{{ list.spas_id }}</p>
                            </td>
                            <td class="text-center">{{list.awarded_year}}</td>
                            <td class="text-center">
                                <b-button @click="view(list)" variant="soft-primary" v-b-tooltip.hover title="View" size="sm" class="edit-list"><i class="ri-eye-fill align-bottom"></i> </b-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr class="text-muted"/>
            <nav aria-label="Page navigation example" v-if="scholars.length != 0">
                <ul class="pagination justify-content-center">
                    <li class="page-item" :class="(currentPage === 1) ? 'disabled' : ''">
                        <button class="page-link" @click="previousPage" :disabled="currentPage === 1" tabindex="-1">Previous</button>
                    </li>
                     <li class="page-item" :class="{ active: 1 === currentPage }">
                        <a class="page-link" @click="gotoPage(1)">1</a>
                    </li>
                    <template v-if="currentPage - 2 > 2">
                    <li class="page-item">
                        <span class="page-link">...</span>
                    </li>
                    </template>
                    <template v-for="pageNumber in limitedPageNumbers" :key="pageNumber">
                    <li class="page-item" :class="{ active: pageNumber === currentPage }">
                        <a class="page-link" @click="gotoPage(pageNumber)">{{ pageNumber }}</a>
                    </li>
                    </template>
                    <template v-if="currentPage + 2 < totalPages - 1">
                    <li class="page-item">
                        <span class="page-link">...</span>
                    </li>
                    </template>
                    <li class="page-item" :class="{ active: totalPages === currentPage }">
                        <a class="page-link" @click="gotoPage(totalPages)">{{ totalPages }}</a>
                    </li>
                    <li class="page-item" :class="(currentPage === totalPages) ? 'disabled' : ''">
                        <button class="page-link" @click="nextPage">Next</button>
                    </li>
                </ul>
            </nav>
        </template>
        <template v-slot:footer>
            <b-button @click="viewScholar = false" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="(option) ? false : true " block>Confirm</b-button>
        </template>
    </b-modal>
</template>

<script>
    export default {
        data() {
            return {
                currentUrl: window.location.origin,
                showModal: false,
                scholars: [],
                scholar: '',
                itemsPerPage: 5,
                currentPage: 1,
                countPage: 1,
                filteredArray: [],
                keyword: '',
                option: '',
                viewScholar: false
            }
        },
        watch: {
            keyword(newVal){
                this.search(newVal)
            }
        },
        computed: {
            totalPages() {
                return (this.scholars) ? Math.ceil(this.scholars.length / this.itemsPerPage) : '';
            },
            paginatedData() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.scholars.slice(start, end);
            },
            limitedPageNumbers() {
                const currentPage = this.currentPage;
                const totalPages = this.totalPages;
                const maxPagesToShow = 2; // Change this number to control how many pages are shown

                let startPage = Math.max(2, currentPage - 2);
                let endPage = Math.min(totalPages - 1, currentPage + 2);

                if (currentPage - 2 <= 2) {
                endPage = Math.min(endPage + (2 - (currentPage - 2)), totalPages - 1);
                }

                if (currentPage + 2 >= totalPages - 1) {
                startPage = Math.max(startPage - (2 - (totalPages - currentPage - 2)), 2);
                }

                const pageNumbers = [];
                for (let i = startPage; i <= endPage; i++) {
                pageNumbers.push(i);
                }

                if (pageNumbers.length > maxPagesToShow) {
                    // If there are more pages than the maximum allowed, adjust the range
                    const midpoint = Math.floor(maxPagesToShow / 2);
                    if (currentPage - startPage < midpoint) {
                        endPage = startPage + maxPagesToShow - 1;
                    } else if (endPage - currentPage < midpoint) {
                        startPage = endPage - maxPagesToShow + 1;
                    }
                    pageNumbers.length = 0;
                    for (let i = startPage; i <= endPage; i++) {
                        pageNumbers.push(i);
                    }
                    }

                return pageNumbers;
            }
        },
        methods: {
            show(data) {
                this.viewScholar = false;
                this.scholars = data;
                this.showModal = true;
            },
            create(){
                this.isLoading = true;
                axios.post(this.currentUrl + '/monitoring', {
                    enrollment_id: this.scholar.id,
                    scholar_id: this.scholar.scholar.id,
                    type: 'termination',
                    option: this.option
                })
                .then(response => {
                    this.isLoading = false;
                    this.hide();
                })
                .catch(err => console.log(err));
            },
            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                    this.countPage = (this.currentPage * 5) - 4;
                }
            },
            previousPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.countPage = (this.currentPage * 5) - 4;
                }
            },
            gotoPage(pageNumber) {
                if (pageNumber >= 1 && pageNumber <= this.totalPages) {
                    this.currentPage = pageNumber;
                    this.countPage = (this.currentPage * 5) - 4;
                }
            },
            search(data) {
                this.filteredArray = this.scholars.filter(item =>
                    item.lastname.toLowerCase().includes(data.toLowerCase())
                );
            },
            view(data){
                this.scholar= data;
                this.viewScholar = true;
            },
            hide(){
                this.showModal = false;
            }
        }
    }
</script>
