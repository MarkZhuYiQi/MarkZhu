<div>
    {include abc.html}
    adsasf
    {include asd.php}
</div>
{foreach:users name=user}
    <div>userName: {blue('user.username')}</div>
    <div>userPass: {user.userPass}</div>
{/foreach}

{blue('testVars')}
{content}