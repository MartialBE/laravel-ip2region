<?php
namespace Martialbe\LaravelIp2region\Console;

use Illuminate\Console\Command;

class Update extends Command 
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ip2region:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新ip2region数据库文件';

    protected $url;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $dbPath = app('ip2region')->getDbPath();
        $this->url = config("ip2region.update");
        if(!file_exists($dbPath)) {
            $this->info("数据库文件不存在，开始直接下载");
            $this->update($dbPath);
            return;
        }
        $this->comment("正在验证本地数据库文件md5值");
        $localMd5 = md5_file($dbPath);
        $this->comment("本地数据库文件md5值为：".$localMd5);
        $this->comment("正在验证远程数据库文件md5值");
        $gitMd5 = md5_file($this->url);
        $this->comment("远程数据库文件md5值为：".$gitMd5);

        if( $localMd5 == $gitMd5 ) {
            $this->info("文件已经是最新的了。");
            return ;
        }
        $this->comment("开始更新");
        $this->update($dbPath);
    }

    public function update($dbPath)
    {
        try {
            file_put_contents($dbPath, fopen($this->url, 'r'));
        } catch (\Throwable $th) {
            $this->error('更新失败，请重试');
            return false;
        }
        $this->info('更新成功');
        return true;
    }
}
