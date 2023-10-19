<template>
    <b-modal v-model="showModal" title="Unreleased Benefits" :hide-footer="(viewSubjects) ? false : true" style="--vz-modal-width: 800px;" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered>    
        <template v-if="!viewScholar">
            <input class="form-control mb-3" v-model="keyword" v-if="scholars != 0" type="text" placeholder="Search Scholar">
            <hr class="text-muted"/>
            <div class="table-responsive">
                <table class="table table-bordered table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr class="fs-11">
                            <th width="7%" class="text-center">#</th>
                            <th width="30%">Name</th>
                            <th width="30%" class="text-center">No. of enrollments</th>
                            <th width="27%" class="text-center">No. of subjects</th>
                            <th width="5%" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list,index) in scholars.lists" v-bind:key="list.id" class="fs-11">
                            <td class="text-center">{{index + 1}}</td>
                            <td>
                                <h5 class="fs-12 mb-0 text-dark">{{ list.firstname }} {{ list.lastname }}</h5>
                                <p class="text-muted fs-11 mb-n1">{{ list.spas_id }}</p>
                            </td>
                            <td class="text-center">{{list.enrollments.length}}</td>
                            <td class="text-center">{{calculateTotalSum(list)}}</td>
                            <td class="text-center">
                                <b-button @click="view(list)" variant="soft-primary" v-b-tooltip.hover title="View Enrollment" size="sm" class="edit-list me-1"><i class="ri-eye-fill align-bottom"></i> </b-button>
                                <b-button @click="view(list)" variant="soft-danger" v-b-tooltip.hover title="Notify" size="sm" class="edit-list"><i class="ri-alarm-warning-fill align-bottom"></i> </b-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination class="ms-2 me-2" v-if="scholars.meta" @fetch="fetch" :lists="scholars.lists.length" :links="scholars.links" :pagination="scholars.meta" />
            </div>
        </template>
        <template v-else>
            <ul class="list-unstyled mb-0 vstack gap-3" v-if="scholar">
                <li>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0" v-if="scholar.avatar">
                            <img :src="currentUrl+'/images/avatars/'+scholar.avatar" alt="" class="avatar-sm rounded">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fs-14 mb-1 text-primary"> {{scholar.firstname}} {{scholar.lastname}}</h6>
                            <span :class="'badge bg-secondary bg-'+scholar.status.color">{{scholar.status.name}}</span>
                        </div>
                    </div>
                </li>
                <hr class="text-muted mt-0" />
            </ul>
            <div class="table-responsive mb-4">
                <template v-if="!viewSubjects">
                    <table class="table table-bordered table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-11">
                                <th width="7%" class="text-center">#</th>
                                <th width="25%" class="text-center">Academic Year</th>
                                <th width="25%" class="text-center">Semester</th>
                                <th width="20%" class="text-center">Level</th>
                                <th width="20%" class="text-center">Total</th>
                                <th width="5%" class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list,index) in scholar.enrollments" v-bind:key="list.id" class="fs-11">
                                <td class="text-center">{{index + 1}}</td>
                                <td class="text-center">
                                    <h5 class="fs-12 mb-0 text-dark">{{ list.semester.academic_year }}</h5>
                                </td>
                                <td class="text-center">{{list.semester.semester.name}}</td>
                                <td class="text-center">{{list.level.others}}</td>
                                <td class="text-center">{{list.lists.length}}</td>
                                <td class="text-center">
                                    <b-button @click="viewLists(list)" variant="soft-primary" v-b-tooltip.hover title="View Enrollment" size="sm" class="edit-list"><i class="ri-eye-fill align-bottom"></i> </b-button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </template>
                <template v-else>
                    <nav aria-label="breadcrumb" class="mb-2">
                        <ol class="breadcrumb p-3 py-2 bg-light mb-0">
                            <li class="breadcrumb-item">{{selected.semester.academic_year}}</li>
                            <li class="breadcrumb-item active" aria-current="page"> {{selected.semester.semester.name}} </li>
                            <li class="breadcrumb-item active" aria-current="page"> {{selected.level.others}} </li>
                        </ol>
                    </nav>
                    <div class="table-responsive" style="height: calc(100vh - 700px);">
                        <table class="table table-bordered table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="fs-11">
                                    <th width="7%" class="text-center">#</th>
                                    <th width="20%" class="text-center">Code</th>
                                    <th width="38%" class="text-center">Subject</th>
                                    <th width="15%" class="text-center">Unit</th>
                                    <th width="20%" class="text-center">Grade</th>
                                    <!-- <th width="5%" class="text-center"></th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list,index) in selected.lists" v-bind:key="list.id" class="fs-11">
                                    <td class="text-center">{{index + 1}}</td>
                                    <td class="text-center">{{ list.code }}</td>
                                    <td class="text-center">{{list.subject}}</td>
                                    <td class="text-center">{{list.unit}}</td>
                                    <td class="text-center">
                                        <select v-model="list.grade" class="form-select form-select-sm mt-n1 mb-n1">
                                            <option :value="null" disabled>-</option>
                                            <option :value="list1.grade" v-for="(list1,index) in scholar.education.gradings" v-bind:key="index">{{list1.grade}}</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </template>
            </div>
        </template>
        <template v-slot:footer>
            <b-button @click="viewSubjects = false" variant="light" block>Cancel</b-button>
            <b-button @click="save()" variant="success" block>Save</b-button>
        </template>
        <Confirm ref="confirm"/>
    </b-modal>
</template>
<script>
import Confirm from '../../../Enrollments/Modals/ConfirmGrade.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
    export default {
        components: { Pagination, Confirm },
        data() {
            return {
                currentUrl: window.location.origin,
                showModal: false,
                scholars: [],
                lists: [],
                scholar: '',
                selected: '',
                keyword: '',
                viewScholar: false,
                viewSubjects: false
            }
        },
        watch: {
            keyword(newVal){
                this.checkSearchStr(newVal)
            }
        },
        methods: {
            show(data) {
                this.scholars = data;
                this.showModal = true;
                this.viewScholar = false;
            },
            checkSearchStr: _.debounce(function(string) {
                this.fetch();
            }, 500),
            fetch(page_url){
                page_url = page_url || '/monitoring';
                axios.get(page_url, {
                    params: {type: 'missed', keyword: this.keyword}
                })
                .then(response => {
                    this.scholars.lists = response.data.data;
                    this.scholars.meta = response.data.meta;
                    this.scholars.links = response.data.links;
                })
                .catch(err => console.log(err));
            },
            save() {
                this.type = 'save';
                let data = new FormData();
                data.append('enrollment_id', (this.selected != undefined) ? this.selected.id : '');
                data.append('scholar_id', (this.scholar != undefined) ? this.scholar.id : '');
                data.append('lists', (this.selected.lists.length != 0) ? JSON.stringify(this.selected.lists) : '');
                data.append('file_type', 'grade');
                data.append('type', 'grade');

                this.$refs.confirm.set(data);
            },
            view(data){
                this.scholar= data;
                this.viewScholar = true;
            },
            viewLists(lists){
                this.selected = lists;
                this.viewSubjects = true;
            },
            formatMoney(value) {
                let val = (value/1).toFixed(2).replace(',', '.')
                return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            calculateTotalSum(list) {
                console.log(list);
                return list.enrollments.reduce((total, enrollment) => total + enrollment.lists.length, 0);
            },
            hide(){
                this.showModal = false;
            }
        }
    }
</script>
