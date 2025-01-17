<script lang="ts">
    export let item: {
      lessonNumber: string;
      subject: string;
      classroom: string;
      teacher: string;
    };
  </script>
  
  <div class="flex p-4 rounded-lg flex-row items-center gap-5 bg-secondary-700 ">
    <!-- hour indication -->
    <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
      {item.lessonNumber}
    </span>
    <!-- details -->
    <div>
      <p class="text-lg font-bold">{item.subject}</p>
      <p>{item.classroom} - {item.teacher}</p>
    </div>
  </div>
