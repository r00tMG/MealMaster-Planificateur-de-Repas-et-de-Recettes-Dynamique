<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <form @submit.prevent>
          <div class="form-group">
            <label for="event_name">Event Name</label>
            <input
                type="text"
                id="event_name"
                class="form-control"
                v-model="newEvent.event_name"
            >
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="start_date">Start Date</label>
                <input
                    type="date"
                    id="start_date"
                    class="form-control"
                    v-model="newEvent.start_date"
                >
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="end_date">End Date</label>
                <input
                    type="date"
                    id="end_date"
                    class="form-control"
                    v-model="newEvent.end_date"
                >
              </div>
            </div>
            <div class="col-md-6 mb-4" v-if="addingMode.value">
              <button class="btn btn-sm btn-primary" @click="addNewEvent">Save Event</button>
            </div>
            <template v-else>
              <div class="col-md-6 mb-4">
                <button class="btn btn-sm btn-success" @click="updateEvent">Update</button>
                <button class="btn btn-sm btn-danger" @click="deleteEvent">Delete</button>
                <button class="btn btn-sm btn-secondary" @click="toggleAddingMode">Cancel</button>
              </div>
            </template>
          </div>
        </form>
      </div>
      <div class="col-md-8">
        <FullCalendar @eventClick="showEvent" :plugins="calendarPlugins" :events="events"/>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import axios from 'axios';

export default {
  name: "Calendar",
  components: {
    FullCalendar
  },
  setup() {
    const calendarPlugins = [dayGridPlugin, interactionPlugin];
    const events = ref([]);
    const newEvent = reactive({
      event_name: '',
      start_date: '',
      end_date: ''
    });
    const addingMode = ref(true);
    const indexToUpdate = ref('');

    const getEvents = async () => {
      const r = await axios.get('http://localhost:8000/api/meal-plans',{
        headers: {
          'Accept':'application/json',
          'Authorization':`Bearer ${localStorage.getItem('token')}`
        }
      })
      const data = await r.data
      console.log(data)
          //.then(resp => events.value = resp.data.data)
          //.catch(err => console.log(err.response.data));
    };

    const addNewEvent = () => {
      axios.post('/api/calendar', { ...newEvent })
          .then(() => {
            getEvents();
            resetForm();
          })
          .catch(err => console.log('Unable to add new event!', err.response.data));
    };

    const showEvent = (arg) => {
      addingMode.value = false;
      const { id, title, start, end } = events.value.find(event => event.id === +arg.event.id);
      indexToUpdate.value = id;
      newEvent.event_name = title;
      newEvent.start_date = start;
      newEvent.end_date = end;
    };

    const updateEvent = () => {
      axios.put(`/api/calendar/${indexToUpdate.value}`, { ...newEvent })
          .then(() => {
            resetForm();
            getEvents();
            toggleAddingMode();
          })
          .catch(err => console.log('Unable to update event!', err.response.data));
    };

    const deleteEvent = () => {
      axios.delete(`/api/calendar/${indexToUpdate.value}`)
          .then(() => {
            resetForm();
            getEvents();
            toggleAddingMode();
          })
          .catch(err => console.log('Unable to delete event!', err.response.data));
    };

    const resetForm = () => {
      Object.keys(newEvent).forEach(key => newEvent[key] = '');
    };

    const toggleAddingMode = () => {
      addingMode.value = !addingMode.value;
    };

    onMounted(() => {
      getEvents();
    });

    return {
      calendarPlugins,
      events,
      newEvent,
      addingMode,
      addNewEvent,
      showEvent,
      updateEvent,
      deleteEvent,
      toggleAddingMode
    };
  }
};
</script>

<style scoped>

.fc-title {
  color: #fff;
}

.fc-title:hover {
  cursor: pointer;
}
</style>
