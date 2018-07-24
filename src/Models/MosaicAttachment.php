<?php
/**
 * Part of the evias/nem-php package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under MIT License.
 *
 * This source file is subject to the MIT License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    evias/nem-php
 * @version    1.0.0
 * @author     Grégory Saive <greg@evias.be>
 * @author     Robin Pedersen (https://github.com/RobertoSnap)
 * @license    MIT License
 * @copyright  (c) 2017-2018, Grégory Saive <greg@evias.be>
 * @link       http://github.com/evias/nem-php
 */
namespace NEM\Models;

/**
 * This is the MosaicAttachment class
 *
 * This class extends the NEM\Models\Model class
 * to provide with an integration of NEM's Mosaic 
 * Attachments objects.
 * 
 * @link https://nemproject.github.io/#mosaic
 */
class MosaicAttachment
    extends Model
{
    /**
     * List of fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        "mosaicId",
        "quantity"
    ];

    /**
     * The model instance's relations configuration
     *
     * @var array
     */
    protected $relations = [
        "mosaicId",
    ];

    /**
     * List of automatic *value casts*.
     *
     * @var array
     */
    protected $casts = [
        "quantity" => "int",
    ];

    /**
     * Address DTO automatically cleans address representation.
     *
     * @return  array       Associative array with key `address` containing a NIS *compliable* address representation.
     */
    public function toDTO($filterByKey = null)
    {
        return [
            "mosaicId" => $this->mosaicId()->toDTO(),
            "quantity" => (int) $this->quantity,
        ];
    }

    /**
     * Overload of the \NEM\Core\Model::serialize() method to provide
     * with a specialization for *mosaicId and Quantity* serialization.
     *
     * @see \NEM\Contracts\Serializable
     * @param   null|string $parameters    non-null will return only the named sub-dtos.
     * @return  array   Returns a byte-array with values in UInt8 representation.
     */
    public function serialize($parameters = null)
    {
        $nisData = $this->toDTO();

        // shortcuts
        $mosaicS = $this->mosaicId()->serialize();
        $quantity = $this->getSerializer()->serializeLong($nisData["quantity"]);

        return $this->getSerializer()->aggregate($mosaicS, $quantity);
    }

    /**
     * Mutator for `mosaicId` relation.
     *
     * This will return a NIS compliant [MosaicId](https://bob.nem.ninja/docs/#mosaicId) object. 
     *
     * @param   array   $mosaidId       Array should contain offsets `namespaceId` and `name`.
     * @return  \NEM\Models\Mosaic
     */
    public function mosaicId(array $mosaicId = null)
    {
        return new Mosaic($mosaicId ?: $this->getAttribute("mosaicId"));
    }
}
