<script lang="ts">
  import ScheduleDayView from "./ScheduleDayView.svelte";
  import ScheduleWeekView from "./ScheduleWeekView.svelte";
  import { ButtonGroup, Button } from "flowbite-svelte";
  import { navigate } from "../../stores/router.store";
  import { Tabs, TabItem } from "flowbite-svelte";
  import type { UserModel } from "../../models/user.model";
  
  export let user: UserModel;
</script>


<div >
<Tabs tabStyle="underline" class="text-center justify-center display:flex">
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
   <ScheduleWeekView/>
  </TabItem>
</Tabs>
</div>



