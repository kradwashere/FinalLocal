<template>
    <h6 class="fs-11 text-muted text-uppercase mb-3">Ongoing Scholars</h6>
    <div class="d-flex align-items-center">
        <div class="flex-shrink-0">
            <i class="ri-team-fill text-dark fs-17"></i>
        </div>
        <div class="flex-grow-1 ms-3 overflow-hidden">
            <div class="progress mb-2 progress-sm">
                <div class="progress-bar bg-dark" role="progressbar" :style="'width: '+(counts.scholars/counts.total)*100+'%'" :aria-valuenow="(counts.enrolled.length/counts.scholars)*100"
                aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <span class="text-muted fs-12 d-block text-truncate"><b>{{counts.scholars}}</b> out of <b>{{counts.total}}</b> are ongoing scholars.</span>
        </div>
    </div>

    <hr class="text-muted"/>

    <h6 class="fs-11 text-muted text-uppercase mb-3">Scholars Enrolled</h6>
    <div class="d-flex align-items-center">
        <div class="flex-shrink-0">
            <i class="ri-group-2-fill text-dark fs-17"></i>
        </div>
        <div class="flex-grow-1 ms-3 overflow-hidden">
            <div class="progress mb-2 progress-sm">
                <div class="progress-bar bg-dark" role="progressbar" :style="'width: '+(counts.enrolled/counts.scholars)*100+'%'" :aria-valuenow="(counts.enrolled.length/counts.scholars)*100"
                aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <span class="text-muted fs-12 d-block text-truncate"><b>{{counts.enrolled}}</b> out of <b>{{counts.scholars}}</b> ongoing scholars are enrolled.</span>
        </div>
    </div>

    <hr class="text-muted"/>

    <h6 class="fs-11 text-muted text-uppercase mb-3">Schools Semester for {{semester_year}} </h6>
    <div class="d-flex align-items-center">
        <div class="flex-shrink-0">
            <i class="ri-hotel-line text-dark fs-17"></i>
        </div>
        <div class="flex-grow-1 ms-3 overflow-hidden">
            <div class="progress mb-2 progress-sm">
                <div class="progress-bar bg-dark" role="progressbar" :style="'width: '+(counts.semesters.length/counts.schools)*100+'%'" :aria-valuenow="(counts.semesters.length/counts.schools)*100"
                aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <span class="text-muted fs-12 d-block text-truncate"><b>{{counts.semesters.length}}</b> out of <b>{{counts.schools}}</b> schools with active semester.</span>
        </div>
    </div>

    <hr class="text-muted"/>

    <div class="mt-2">
        <ul class="list-unstyled vstack gap-3" style="cursor:pointer;">
            <li>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-xs">
                            <div class="avatar-title rounded bg-soft-primary text-primary">
                            <i class="bx bxs-graduation fs-17"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0 fs-13">Graduating Scholars</h5>
                        <p class="mb-0 fs-12 text-muted">Qualified for graduation</p>
                    </div>
                    <div class="avatar-group">
                        <div class="avatar-group-item" v-for="user in scholarsGraduating" v-bind:key="user.id">
                            <a class="d-inline-block" v-b-tooltip.hover :title="user.firstname+' '+user.lastname">
                                <img :src="currentUrl+'/images/avatars/'+user.avatar" alt="" class="rounded-circle avatar-xxs">
                            </a>
                        </div>
                        <div class="avatar-group-item" v-if="counts.graduating.length > 0"> 
                            <a class="" href="javascript: void(0);" target="_self" v-b-tooltip.hover :title="counts.graduating.length +' more scholars'">
                                <div class="avatar-xxs">
                                    <span class="avatar-title rounded-circle bg-info text-white">+</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </li>   
            <li @click="openGrades(grades)">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-xs">
                            <div class="avatar-title rounded bg-soft-secondary text-secondary">
                            <i class="ri-file-text-line fs-17"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0 fs-13">Lacking Grades</h5>
                        <p class="mb-0 fs-12 text-muted">No Grades in an inactive semester</p>
                    </div>
                    <div class="avatar-group">
                        <div class="avatar-group-item" v-for="user in grades.lists" v-bind:key="user.id">
                            <a class="d-inline-block" v-b-tooltip.hover :title="user.firstname+' '+user.lastname">
                                <img :src="currentUrl+'/images/avatars/'+user.avatar" alt="" class="rounded-circle avatar-xxs">
                            </a>
                        </div>
                        <div class="avatar-group-item" v-if="grades.meta.total > 5"> 
                            <a class="" href="javascript: void(0);" target="_self" v-b-tooltip.hover :title="grades.meta.total - 5+' more scholars'">
                                <div class="avatar-xxs">
                                    <span class="avatar-title rounded-circle bg-success text-white">+</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            <li @click="openBenefits(benefits)">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-xs">
                            <div class="avatar-title rounded bg-soft-success text-success">
                            <i class="ri-wallet-line fs-17"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0 fs-13">Unreleased Benefits</h5>
                        <p class="mb-0 fs-12 text-muted">Stipend not released</p>
                    </div>
                    <div class="avatar-group">
                        <div class="avatar-group-item" v-for="user in benefits.lists" v-bind:key="user.id">
                            <a class="d-inline-block" v-b-tooltip.hover :title="user.firstname+' '+user.lastname">
                                <img :src="currentUrl+'/images/avatars/'+user.avatar" alt="" class="rounded-circle avatar-xxs">
                            </a>
                        </div>
                        <div class="avatar-group-item" v-if="benefits.meta.total > 5"> 
                            <a class="" href="javascript: void(0);" target="_self" v-b-tooltip.hover :title="benefits.meta.total - 5+' more scholars'">
                                <div class="avatar-xxs">
                                    <span class="avatar-title rounded-circle bg-success text-white">+</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            <li @click="openUnenrolled(unenrolled)">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-xs">
                            <div class="avatar-title rounded bg-soft-info text-info">
                            <i class="ri-question-fill fs-17"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0 fs-13">Unenrolled Scholars</h5>
                        <p class="mb-0 fs-12 text-muted">Schools with active semester</p>
                    </div>
                    <div class="avatar-group">
                        <div class="avatar-group-item" v-for="user in unenrolled.lists" v-bind:key="user.id">
                            <a class="d-inline-block" v-b-tooltip.hover :title="user.firstname+' '+user.lastname">
                                <img :src="currentUrl+'/images/avatars/'+user.avatar" alt="" class="rounded-circle avatar-xxs">
                            </a>
                        </div>
                        <div class="avatar-group-item" v-if="unenrolled.meta.total > 5"> 
                            <a class="" href="javascript: void(0);" target="_self" v-b-tooltip.hover :title="unenrolled.meta.total - 5+' more scholars'">
                                <div class="avatar-xxs">
                                    <span class="avatar-title rounded-circle bg-success text-white">+</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </li> 
            <li @click="openMissed(missed)">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-xs">
                            <div class="avatar-title rounded bg-soft-warning text-warning">
                            <i class="ri-alert-fill fs-17"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0 fs-13">Missed Enrollment</h5>
                        <p class="mb-0 fs-12 text-muted">No COR submitted</p>
                    </div>
                    <div class="avatar-group">
                        <div class="avatar-group-item" v-for="user in missed.lists" v-bind:key="user.id">
                            <a class="d-inline-block" v-b-tooltip.hover :title="user.firstname+' '+user.lastname">
                                <img :src="currentUrl+'/images/avatars/'+user.avatar" alt="" class="rounded-circle avatar-xxs">
                            </a>
                        </div>
                        <div class="avatar-group-item" v-if="missed.meta.total > 5"> 
                            <a class="" href="javascript: void(0);" target="_self" v-b-tooltip.hover :title="missed.meta.total -5 +' more scholars'">
                                <div class="avatar-xxs">
                                    <span class="avatar-title rounded-circle bg-success text-white">+</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
             <li @click="openTermination(scholarsTermination)"> 
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-xs">
                            <div class="avatar-title rounded bg-soft-danger text-danger">
                            <i class="ri-error-warning-line fs-17"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0 fs-13">For Termination</h5>
                        <p class="mb-0 fs-12 text-muted">2 Grades Failed in a semester</p>
                    </div>
                    <div class="avatar-group">
                        <div class="avatar-group-item" v-for="user in scholarsTermination" v-bind:key="user.id">
                            <a class="d-inline-block" v-b-tooltip.hover :title="user.scholar.firstname+' '+user.scholar.lastname">
                                <img :src="currentUrl+'/images/avatars/'+user.scholar.avatar" alt="" class="rounded-circle avatar-xxs">
                            </a>
                        </div>
                        <div class="avatar-group-item" v-if="counts.termination.length > 0"> 
                            <a class="" href="javascript: void(0);" target="_self" v-b-tooltip.hover :title="counts.termination.length +' more scholars'">
                                <div class="avatar-xxs">
                                    <span class="avatar-title rounded-circle bg-info text-white">+</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <Termination ref="termination"/>
    <Missed ref="missed"/>
    <Unenrolled ref="unenrolled"/>
    <Benefits ref="benefits"/>
    <Grades ref="grades"/>
</template>
<script>
import Missed from './Modals/Sub/Missed.vue';
import Grades from './Modals/Sub/Grades.vue';
import Benefits from './Modals/Sub/Benefits.vue';
import Unenrolled from './Modals/Sub/Unenrolled.vue';
import Termination from './Modals/Sub/Termination.vue';
export default {
    components : { Termination, Missed, Unenrolled, Benefits, Grades },
    props: ['semester_year'],
    data(){
        return {
            currentUrl: window.location.origin,
            counts: { semesters: [], enrolled: [],  termination: [],  graduating: [],},
            missed: {lists: [],meta: {}, links: {}},
            unenrolled: { lists: [], meta: {},links: {}},
            benefits: {lists: [], meta: {},links: {}},
            grades: {lists: [], meta: {},links: {}}
        }
    },
    created(){
        this.fetch();
        this.fetchMissed();
        this.fetchUnenrolled();
        this.fetchBenefits();
        this.fetchGrades();
    },
    watch: {
        data: {
            deep: true,
            handler(val = null) {
                if(val != null && val !== ''){
                    console.log('watched');
                }
            },
        },
        data() {
            return this.$page.props.flash.data;
        },
    },
    computed: {
        scholarsTermination: function () {
            return (this.counts.termination.length > 0) ? this.counts.termination.splice(0,3) : [];
        },
        scholarsGraduating: function () {
            return (this.counts.graduating.length > 0) ? this.counts.graduating.splice(0,3) : [];
        }
    },
    methods: {
        fetch(){
            axios.get(this.currentUrl+'/monitoring', {
                params: {
                    type: 'counts',
                    semester_year: this.semester_year
                }
            })
            .then(response => {
                this.counts = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchMissed(){
            axios.get(this.currentUrl+'/monitoring', {
                params: {type: 'missed'}
            })
            .then(response => {
                this.missed.lists = response.data.data;
                this.missed.meta = response.data.meta;
                this.missed.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        fetchUnenrolled(){
            axios.get(this.currentUrl+'/monitoring', {
                params: {type: 'unenrolled'}
            })
            .then(response => {
                this.unenrolled.lists = response.data.data;
                this.unenrolled.meta = response.data.meta;
                this.unenrolled.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        fetchBenefits(){
            axios.get(this.currentUrl+'/monitoring', {
                params: {type: 'benefits'}
            })
            .then(response => {
                this.benefits.lists = response.data.data;
                this.benefits.meta = response.data.meta;
                this.benefits.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        fetchGrades(){
            axios.get(this.currentUrl+'/monitoring', {
                params: {type: 'grades'}
            })
            .then(response => {
                this.grades.lists = response.data.data;
                this.grades.meta = response.data.meta;
                this.grades.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        openTermination(data){
            if(data.length > 0){
                this.$refs.termination.show(data);
            }
        },
        openMissed(data){
            if(data.meta.total > 0){
                this.$refs.missed.show(data);
            }
        },
        openUnenrolled(data){
            if(data.meta.total > 0){
                this.$refs.unenrolled.show(data);
            }
        },
        openBenefits(data){
            if(data.meta.total > 0){
                this.$refs.benefits.show(data);
            }
        },
        openGrades(data){
            if(data.meta.total > 0){
                this.$refs.grades.show(data);
            }
        }
    }
}
</script>