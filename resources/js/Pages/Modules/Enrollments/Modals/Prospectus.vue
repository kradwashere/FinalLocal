<template>
    <b-modal v-model="showModal" title="Prospectus" hide-footer header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
  
        <div class="modal-body text-center">
            <lottie colors="primary:#121331,secondary:#08a88a" trigger="loop"
                :options="defaultOptions1" :height="120" :width="120" />
            <div class="mt-n3">
                <!-- <h4 class="mb-3">You've made it!</h4> -->
                <i style="font-size: 40px;" class="ri-alert-fill text-danger mb-4"></i>
                <p class="mb-2 mt-2">We have identified some changes in your prospectus.</p>
                <p class="text-muted fs-11 mb-4">To prevent future errors, please ensure the prospectus is updated accordingly, as enrollment is based on the prospectus.</p>
                <div class="hstack gap-2 justify-content-center mt-3">
                    <button @click="save()" class="btn btn-primary">Update now</button>
                </div>
            </div>
        </div>

    </b-modal>
</template>

<script>
    export default {
        data() {
            return {
                currentUrl: window.location.origin,
                showModal: false,
                isLoading: false,
                prospectus: 0,
                education_id: '',
                id: ''
            }
        },
        methods: {
            show(data,education_id,id) {
                this.id = id;
                this.education_id = education_id;
                this.prospectus = data;
                this.showModal = true;
            },
            save(){
                this.form = this.$inertia.form({
                    id: this.education_id,
                    subcourse_id: this.id,
                    type: 'newprospectus',
                })

                this.form.put('/schools/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.showModal = false;
                    }
                });
            },
            hide(){
                this.showModal = false;
            }
        }
    }
</script>
