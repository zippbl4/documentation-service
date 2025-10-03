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
        <component
          v-for="panel in panels"
          :key="panel.id"
          :panel="panel"
          :name="panel.name"
          :fields="panel.fields"
          :form-unique-id="124214"
        />
      </div>

      <!-- Update Button -->
      <div
        class="flex flex-col md:flex-row md:items-center justify-center md:justify-end space-y-2 md:space-y-0 space-x-3">
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
      panels: [],
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
        .get('/nova-vendor/aspect/')
        .then((response) => {
          this.title = response.data.title
          this.fields = response.data.fields
          this.fields = response.data.panels

          console.log(response.data)
        });

      this.handleResourceLoaded()
    },

    submitViaUpdateResource(e) {
      e.preventDefault()
    },
  }
}
</script>

<style>
/* Scoped Styles */
</style>
