// app/Models/LaundryItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaundryItem extends Model
{
    protected $table = 'laundryitem';
    public $timestamps = false;

    protected $fillable = [
        'OrderID', 'ItemName', 'Weight', 'Price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderID');
    }
}
