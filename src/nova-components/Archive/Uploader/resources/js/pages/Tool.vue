<template>
    <LoadingView :loading="loading">
        <form
            :data-form-unique-id="124214"
            @submit="submitViaUpdateResource"
            autocomplete="off"
            ref="form"

        >
            <Heading class="mb-6">{{ title }}</Heading>

            <!-- Fields -->
            <div class="mb-8 space-y-4">
                <Card class="overflow-hidden">
                    <div v-for="field in fields">
                        <component
                            :is="'form-' + field.component"
                            :field="field"
                        />
                    </div>
                </Card>
            </div>

            <!-- Update Button -->
            <div class="flex flex-col md:flex-row md:items-center justify-center md:justify-end space-y-2 md:space-y-0 space-x-3">
                <LoadingButton
                    dusk="update-button"
                    align="center"
                    type="submit"
                >
                    Upload
                </LoadingButton>
            </div>
        </form>
    </LoadingView>
</template>

<script>
import each from 'lodash/each'
import tap from 'lodash/tap'

export default {
    created() {
        this.getFields()
    },
    data() {
        return {
            loading: true,
            title: null,
            fields: [],
        }
    },
    methods: {
        /**
         * Handle resource loaded event.
         */
        handleResourceLoaded() {
            this.loading = false
        },

        /**
         * Get the available fields for the resource.
         */
        getFields() {
            Nova
                .request()
                .get('/nova-vendor/archive-uploader/')
                .then((response) => {
                    this.title = response.data.title
                    this.fields = response.data.fields
                    console.log(response.data)
                });

            this.handleResourceLoaded()
        },

        submitViaUpdateResource(e) {
            e.preventDefault()
            this.updateRequest()
        },

        updateRequest() {
            Nova
                .request()
                .post('/nova-vendor/archive-uploader/', this.updateResourceFormData(), {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function (response) {
                    console.log(response);
                });
        },

        /**
         * Create the form data for creating the resource.
         */
        updateResourceFormData() {
            return tap(new FormData(), formData => {
                each(this.fields, field => {
                    field.fill(formData)
                })
            })
        },
    }
}
</script>

<style>
/* Scoped Styles */
</style>
