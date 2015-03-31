<p><a href={createUrl}>新建</a></p>

<form action={submitContestId} method="get">
    比赛id：
    <input type='input' name='contestId' size='10' value={contestId} >
    <input type='submit' value='筛选'>
</form>


{teamsTable}

