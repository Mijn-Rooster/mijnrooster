<script lang="ts">
  import ScheduleDayView from "./ScheduleDayView.svelte";
  import ScheduleWeekView from "./ScheduleWeekView.svelte";
  import { Tabs, TabItem } from "flowbite-svelte";
  import type { UserModel } from "../../models/user.model";

  export let user: UserModel;
</script>


<Tabs tabStyle="underline" class="text-center justify-center display:flex" data-numpad="toggle-view">
  <TabItem open>
    <div slot="title" class="flex items-center gap-2 text-xl">
      Dag
    </div>
    <ScheduleDayView {user}/>
  </TabItem>
  <TabItem>
    <div slot="title" class="flex items-center gap-2 text-xl">
      Week
    </div>
   <ScheduleWeekView {user}/>
  </TabItem>
</Tabs>



